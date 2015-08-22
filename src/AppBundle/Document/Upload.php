<?php

namespace AppBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Doctrine\MongoDB\GridFSFile;

/**
 * cf: http://php-and-symfony.matthiasnoback.nl/2012/10/uploading-files-to-mongodb-gridfs-2/
 */

/**
 * @MongoDB\Document
 */
class Upload extends Document
{
    /**
     * @MongoDB\File
     * @var GridFSFile
     */
    private $file;

    /**
     * @MongoDB\String
     * @var string
     */
    private $filename;

    /**
     * @MongoDB\String
     * @var string
     */
    private $mimeType;

    /**
     * @MongoDB\Date
     * @var \DateTime
     */
    private $uploadDate;

    /**
     * @MongoDB\Int
     * @var int
     */
    private $length;

    /**
     * @MongoDB\Int
     * @var int
     */
    private $chunkSize;

    /**
     * @MongoDB\String
     * @var string
     */
    private $md5;

    /**
     * @return GridFSFile
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param GridFSFile $file
     * @return self
     */
    public function setFile($file)
    {
        $this->file = $file;
        return $this;
    }

    /**
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @param string $filename
     * @return self
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;
        return $this;
  }

    /**
     * @return string
     */
    public function getMimeType()
    {
        return $this->mimeType;
    }

    /**
     * @param string $mimeType
     * @return self
     */
    public function setMimeType($mimeType)
    {
        $this->mimeType = $mimeType;
        return $this;
  }

    /**
     * @return int
     */
    public function getChunkSize()
    {
        return $this->chunkSize;
    }

    /**
     * @return int
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * @return string
     */
    public function getMd5()
    {
        return $this->md5;
    }

    /**
     * @return \DateTime
     */
    public function getUploadDate()
    {
        return $this->uploadDate;
    }

    /**
     * Set uploadDate
     *
     * @param \DateTime $uploadDate
     * @return self
     */
    public function setUploadDate($uploadDate)
    {
        $this->uploadDate = $uploadDate;
        return $this;
    }

    /**
     * Set length
     *
     * @param int $length
     * @return self
     */
    public function setLength($length)
    {
        $this->length = $length;
        return $this;
    }

    /**
     * Set chunkSize
     *
     * @param int $chunkSize
     * @return self
     */
    public function setChunkSize($chunkSize)
    {
        $this->chunkSize = $chunkSize;
        return $this;
    }

    /**
     * Set md5
     *
     * @param string $md5
     * @return self
     */
    public function setMd5($md5)
    {
        $this->md5 = $md5;
        return $this;
    }
}
