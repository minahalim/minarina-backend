<?php

declare (strict_types = 1);

namespace App\Application\Actions\Product;

use Psr\Http\Message\ResponseInterface as Response;

class ProductUploadAction extends ProductAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {

        $upload_path = $this->settings->get('upload_path');

        $files = $this->request->getUploadedFiles();

        // handle single input with single file upload
        $uploadedFile = $files['photo'];

        $this->logger->info("Product image upload.");

        if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
            $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
            $basename = bin2hex(random_bytes(8)); // see http://php.net/manual/en/function.random-bytes.php
            $filename = sprintf('%s.%0.8s', $basename, $extension);

            $uploadedFile->moveTo($upload_path . $filename);

            // Build the HTTP response
            return $this->respondWithData(["fileName" => $filename]);
        } else {
            return $this->respondWithData(["error" => 1]);
        }
    }
}
