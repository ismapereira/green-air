<?php
/**
 * Helper centralizado para upload de imagens.
 * Substitui as 4 implementações duplicadas nos controllers.
 */
class UploadHelper
{
    /**
     * Processa upload de imagem de um campo de formulário.
     *
     * @param string $fieldName  Nome do campo file no form (ex: 'photo')
     * @param string $destDir    Diretório de destino absoluto
     * @param string $prefix     Prefixo do nome do arquivo (ex: 'tree', 'user')
     * @return string|null       Nome do arquivo salvo ou null se falhou
     */
    public static function handleImage(string $fieldName, string $destDir, string $prefix = 'file'): ?string
    {
        if (empty($_FILES[$fieldName]['tmp_name']) || $_FILES[$fieldName]['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        $tmpFile = $_FILES[$fieldName]['tmp_name'];
        $fileSize = $_FILES[$fieldName]['size'];
        $origName = $_FILES[$fieldName]['name'];

        // Validar MIME type
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($tmpFile);
        if (!in_array($mime, ALLOWED_IMAGE_TYPES)) {
            return null;
        }

        // Validar tamanho
        if ($fileSize > MAX_FILE_SIZE) {
            return null;
        }

        // Criar diretório se não existir
        if (!is_dir($destDir)) {
            mkdir($destDir, 0755, true);
        }

        // Gerar nome aleatório
        $ext = strtolower(pathinfo($origName, PATHINFO_EXTENSION) ?: 'jpg');
        $allowedExts = ['jpg', 'jpeg', 'png', 'webp'];
        if (!in_array($ext, $allowedExts)) {
            $ext = 'jpg';
        }
        $name = $prefix . '_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;

        // Mover arquivo
        if (move_uploaded_file($tmpFile, $destDir . '/' . $name)) {
            return $name;
        }

        return null;
    }

    /**
     * Remove um arquivo de upload antigo.
     */
    public static function deleteOld(?string $filename, string $dir): void
    {
        if (!$filename) return;
        $path = $dir . '/' . $filename;
        if (is_file($path)) {
            @unlink($path);
        }
    }
}
