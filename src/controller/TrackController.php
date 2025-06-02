<?php


class TrackController extends DefaultController {

    private $track;

    public function __construct($request) {
        parent::__construct($request);
        $this->track = new Track();
    }

    public function search() {
        try {
            $searchTerm = $this->request->getQueryParam('s');
            $composer = $this->request->getQueryParam('composer');

            if ($composer) {
                $tracks = $this->track->getByComposer($composer);

            } elseif ($searchTerm) {
                $tracks = $this->track->search($searchTerm);

            } else {
                return $this->response(['error' => 'Search term or composer is required'], 400);
            }

            if (!$tracks) {
                return $this->response(['error' => 'No tracks found'], 404);
            }

            return $this->response($tracks);

        } catch(Exception $e) {
            return $this->response(['error' => $e->getMessage()], 500);
        }
    }

    public function getById(int $id) {
        try {
            $track = $this->track->getById($id);
            if(!$track) {
                return $this->response(['error' => 'Track not found'], 404);
            }
            return $this->response($track);

        } catch(Exception $e) {
            return $this->response(['error' => $e->getMessage()], 500);
        }
        
    }


    public function add() {
        try {
            $data = $this->request->body();

            $required = ['name', 'album_id', 'media_type_id', 'genre_id', 'composer', 'milliseconds', 'bytes', 'unit_price'];
            foreach ($required as $field) {
                if (!isset($data[$field])) {
                    return $this->response(['error' => "$field is required"], 400);
                }
            }

            $data['name'] = strip_tags(trim($data['name']));
            $data['composer'] = strip_tags(trim($data['composer']));

            $trackId = $this->track->add([
                $data['name'],
                $data['album_id'],
                $data['media_type_id'],
                $data['genre_id'],
                $data['composer'],
                $data['milliseconds'],
                $data['bytes'],
                $data['unit_price']
            ]);

            if ($trackId) {
                $createdTrack = $this->track->getById($trackId);
                return $this->response($createdTrack, 201);
            } else {
                return $this->response(['error' => 'Failed to create track'], 500);
            }

        } catch(Exception $e) {
            return $this->response(['error' => $e->getMessage()], 500);
        }
        
    }

    public function put(int $id) {
        try {
            if(!$this->track->getById($id)) {
                return $this->response(['error' => 'Track not found'], 404);
            }

            $data = $this->request->body();

            $keyMap = [
                'name' => 'Name',
                'album_id' => 'AlbumId',
                'media_type_id' => 'MediaTypeId',
                'genre_id' => 'GenreId',
                'composer' => 'Composer',
                'milliseconds' => 'Milliseconds',
                'bytes' => 'Bytes',
                'unit_price' => 'UnitPrice'
            ];

            $updateFields = [];

            foreach ($keyMap as $field => $dbColumn) {
                if (isset($data[$field])) {
                    $value = $data[$field];

                    if(in_array($field, ['name', 'composer'])) {
                        $value = strip_tags(trim($value));
                    }

                    $updateFields[$dbColumn] = $value;
                }
            }

            if (empty($updateFields)) {
                return $this->response(['error' => 'No valid fields'], 400);
            }

            $success = $this->track->put($id, $updateFields);

            if (!$success) {
                return $this->response(['error' => 'Failed to update track'], 500);
            }

            $updatedTrack = $this->track->getById($id);
            return $this->response($updatedTrack);

        } catch(Exception $e) {
            return $this->response(['error' => "Failed to update track"], 500);
        }
        
    }

    public function delete(int $id) {
        try {
            $success = $this->track->delete($id);
            if(!$success) {
                return $this->response(['error' => 'Could not delete'], 400);
            }

            return $this->response(['data' => "Track id: " . $id . " deleted"], 200);

        } catch(Exception $e) {
            return $this->response(['error' => $e->getMessage()], 500);
        }
        
    }

}