<?php


class MediaTypeController extends DefaultController {

    private $mediaType;

    public function __construct($requst) {
        parent::__construct($requst);
        $this->mediaType = new MediaType();
    }

    public function getAll() {
        try {
            $mediaTypes = $this->mediaType->getAll();
        
            if(!$mediaTypes) {
                return $this->response(['error' => 'No media types found'], 404);
            }

            return $this->response($mediaTypes);

        } catch(Exception $e) {
            return $this->response(['error' => $e->getMessage()], 500);
        }
        
    }
}