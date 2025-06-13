<?php

$foods = [
    'osh' => 'https://images.unsplash.com/photo-1601050690597-df0568f70950?w=800',
    'manti' => 'https://images.unsplash.com/photo-1601050690597-df0568f70950?w=800',
    'lagmon' => 'https://images.unsplash.com/photo-1601050690597-df0568f70950?w=800',
    'somsa' => 'https://images.unsplash.com/photo-1601050690597-df0568f70950?w=800',
    'chuchvara' => 'https://images.unsplash.com/photo-1601050690597-df0568f70950?w=800',
    'mastava' => 'https://images.unsplash.com/photo-1601050690597-df0568f70950?w=800',
    'dimlama' => 'https://images.unsplash.com/photo-1601050690597-df0568f70950?w=800',
    'qozon_kabob' => 'https://images.unsplash.com/photo-1601050690597-df0568f70950?w=800',
    'norin' => 'https://images.unsplash.com/photo-1601050690597-df0568f70950?w=800',
    'moshxorda' => 'https://images.unsplash.com/photo-1601050690597-df0568f70950?w=800',
    'shashlik' => 'https://images.unsplash.com/photo-1601050690597-df0568f70950?w=800',
    'qovurma' => 'https://images.unsplash.com/photo-1601050690597-df0568f70950?w=800',
    'shorva' => 'https://images.unsplash.com/photo-1601050690597-df0568f70950?w=800',
    'qutab' => 'https://images.unsplash.com/photo-1601050690597-df0568f70950?w=800',
    'baliq' => 'https://images.unsplash.com/photo-1601050690597-df0568f70950?w=800',
    'tovuq' => 'https://images.unsplash.com/photo-1601050690597-df0568f70950?w=800'
];

$storagePath = __DIR__ . '/storage/app/public/foods';

if (!file_exists($storagePath)) {
    mkdir($storagePath, 0755, true);
}

foreach ($foods as $name => $url) {
    $imagePath = $storagePath . '/' . $name . '.jpg';
    if (!file_exists($imagePath)) {
        $imageContent = file_get_contents($url);
        if ($imageContent !== false) {
            file_put_contents($imagePath, $imageContent);
            echo "Downloaded: {$name}.jpg\n";
        } else {
            echo "Failed to download: {$name}.jpg\n";
        }
    } else {
        echo "Already exists: {$name}.jpg\n";
    }
}

echo "Done!\n"; 