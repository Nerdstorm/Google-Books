<?php

namespace Nerdstorm\GoogleBooks\Query;

/**
 * Class VolumeSearchQuery
 *
 * Search for volumes that contain this text string. There are special keywords you can specify in the search terms to
 * search in particular fields, such as:
 *
 *  intitle: Returns results where the text following this keyword is found in the title.
 *  inauthor: Returns results where the text following this keyword is found in the author.
 *  inpublisher: Returns results where the text following this keyword is found in the publisher.
 *  subject: Returns results where the text following this keyword is listed in the category list of the volume.
 *  isbn: Returns results where the text following this keyword is the ISBN number.
 *  lccn: Returns results where the text following this keyword is the Library of Congress Control Number.
 *  oclc: Returns results where the text following this keyword is the Online Computer Library Center number.
 *
 * @package Nerdstorm\GoogleBooks\Entity
 */
class VolumeSearchQuery implements QueryInterface
{

    /** @var string */
    protected $query;

    /** @var string */
    protected $author_name;

    /** @var string */
    protected $title;

    /** @var string */
    protected $isbn;

    /** @var string */
    protected $oclc;

    /** @var string */
    protected $subject;

    /** @var string */
    protected $publisher;

    /**
     * @param string $query build an object with a basic search query
     */
    public function __construct($query)
    {
        $this->setQuery($query);
    }

    /**
     * @param string $query
     *
     * @return VolumeSearchQuery
     */
    public function setQuery($query)
    {
        $this->query = $query;

        return $this;
    }

    /**
     * @param string $author_name
     *
     * @return VolumeSearchQuery
     */
    public function setAuthorName($author_name)
    {
        $this->author_name = $author_name;

        return $this;
    }

    /**
     * @param string $title
     *
     * @return VolumeSearchQuery
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @param string $isbn
     *
     * @return VolumeSearchQuery
     */
    public function setIsbn($isbn)
    {
        $this->isbn = $isbn;

        return $this;
    }

    /**
     * @param string $oclc
     *
     * @return VolumeSearchQuery
     */
    public function setOclc($oclc)
    {
        $this->oclc = $oclc;

        return $this;
    }

    /**
     * @param string $subject
     *
     * @return VolumeSearchQuery
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * @param string $publisher
     *
     * @return VolumeSearchQuery
     */
    public function setPublisher($publisher)
    {
        $this->publisher = $publisher;

        return $this;
    }

    public function __toString()
    {
        $query = '';

        if ($this->query) {
            $query .= $this->query;
        }

        if ($this->title) {
            $query .= ' intitle:';
        }

        return urlencode($query);;
    }

}