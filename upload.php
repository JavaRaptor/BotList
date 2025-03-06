<?php
// Chemin où vous souhaitez enregistrer les images téléchargées
$uploadDir = 'uploads/';

// Vérifie si le répertoire d'upload existe, sinon le crée
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// Génère un nom de fichier unique
$fileName = uniqid('image_') . '.' . pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

// Chemin complet du fichier sur le serveur
$filePath = $uploadDir . $fileName;

// Enregistre le fichier téléchargé
move_uploaded_file($_FILES['file']['tmp_name'], $filePath);

// Retourne le chemin du fichier enregistré (ou une réponse JSON avec d'autres informations si nécessaire)
echo json_encode(['filePath' => $filePath]);
?>