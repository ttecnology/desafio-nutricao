<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImportOpenFoodData extends Command
{
    protected $signature = 'import:open-food-data';
    protected $description = 'Import data from Open Food Facts';

    public function handle()
    {
        $this->info('Iniciando importação...');

        $directory = 'temp';
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }

        $files = $this->getOpenFoodFiles();

        foreach ($files as $file) {
            $this->info("Processando arquivo: $file");

            // Baixa o arquivo compactado
            $compressedFile = $this->downloadFile($file);

            // Extrai o arquivo
            $jsonFile = $this->extractFile($compressedFile);

            // Importa os dados
            $data = json_decode(Storage::get($jsonFile));
            dd($data);
            $this->importData($data);

            $this->info("Importação do arquivo $file concluída!");
        }

        $this->info('Importação concluída!');
    }

    protected function getOpenFoodFiles()
    {
        $indexFile = Http::get('https://challenges.coode.sh/food/data/json/index.txt')->body();
        return explode("\n", $indexFile, -1); // Remova a última linha em branco
    }

    protected function downloadFile($file)
    {
        $url = "https://challenges.coode.sh/food/data/json/{$file}";
        $compressedFile = "temp/{$file}.gz"; // Armazena na pasta temp
        Storage::put($compressedFile, Http::get($url)->body());

        return $compressedFile;
    }

    protected function extractFile($compressedFile)
    {
        // Cria um stream de leitura do arquivo compactado
        $stream = gzopen(storage_path("app/{$compressedFile}"), 'r');

        if (!$stream) {
            $this->error("Falha ao abrir o arquivo compactado: {$compressedFile}");
            return null;
        }

        // Gera um nome de arquivo único
        $jsonFileName = 'temp/' . uniqid('extracted_', true) . '.json';

        // Abre o arquivo para escrita
        $jsonFile = fopen(storage_path("app/{$jsonFileName}"), 'w');
        
        if (!$jsonFile) {
            $this->error("Falha ao criar o arquivo JSON: {$jsonFileName}");
            gzclose($stream); // Fecha o stream do arquivo compactado
            return null;
        }

        $incompleteJson = '';

        // Processa cada linha (lote)
        while (!gzeof($stream)) {
            // Lê uma linha do arquivo compactado
            $line = gzgets($stream, 4096);
    
            if ($line === false) {
                $this->error("Falha ao ler uma linha do arquivo compactado: {$compressedFile}");
                break;
            }
    
            // Adiciona a linha à variável de JSON incompleto
            $incompleteJson .= $line;
    
            // Tenta decodificar o JSON
            $jsonContent = json_decode($incompleteJson, true);
    
            // Se a decodificação for bem-sucedida, importa os dados
            if ($jsonContent !== null) {
                $this->importData($jsonContent);
    
                // Limpa a variável de JSON incompleto
                $incompleteJson = '';
            }
        }
        
        // Fecha os arquivos
        fclose($jsonFile);
        gzclose($stream);

        return $jsonFileName;
    }

    protected function importData($data)
    { 
            // Verifica se serving_quantity está presente e é válido
                $servingQuantity = !empty($data['serving_quantity']) && is_numeric($data['serving_quantity'])
                ? $data['serving_quantity']
                : null;

                $nutriscoreScore = !empty($data['nutriscore_score']) && is_numeric($data['nutriscore_score'])
                ? $data['nutriscore_score']
                : null;
                
                $lastModified = isset($data['last_modified_t']) ? date('Y-m-d H:i:s', $data['last_modified_t']) : null;

                // Atribui os valores corrigidos ao array de dados
                $data['serving_quantity'] = $servingQuantity;
                $data['nutriscore_score'] = $nutriscoreScore;
                $data['last_modified_t'] = $lastModified;

                $product = new Product($data);
                $product->save();
    }
}