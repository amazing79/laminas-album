<?php

namespace Album\Model;

use RuntimeException;
use Laminas\Db\TableGateway\TableGatewayInterface;

class AlbumTable
{
    private TableGatewayInterface $tableGateway;
    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }
    public function fetchAll()
    {
        return $this->tableGateway->select();
    }

    public function getAlbum($id)
    {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(['id' => $id]);
        $row = $rowset->current();
        if (! $row) {
            throw new RuntimeException(sprintf('Could not find row with identifier %d', $id));
        }
        return $row;
    }

    public function saveAlbum(Album $album)
    {
        $data = [
            'title' => $album->title,
            'artist' => $album->artist,
        ];
        $id = (int) $album->id;
        if ($id === 0) {
            $this->tableGateway->insert($data);
        } else {
            try {
                $this->getAlbum($id);
                $this->tableGateway->update($data, ['id' => $id]);
            } catch (RuntimeException $e) {
                throw new RuntimeException(sprintf('Cannot update album with identifier %d', $id));
            }
        }
    }
    public function deleteAlbum($id)
    {
        $this->tableGateway->delete(['id' => (int) $id]);
    }
}
