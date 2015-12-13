<?php

namespace tests\Nerdstorm\GoogleBooks\Service;

use Nerdstorm\GoogleBooks\Annotations\Mapper\AnnotationMapper;
use Nerdstorm\GoogleBooks\Entity\BookPrice;
use Nerdstorm\GoogleBooks\Entity\Volume;

class AnnotationMapperTest extends \PHPUnit_Framework_TestCase
{
    protected $book_volume = '
    {
       "kind":"books#volume",
       "id":"2kjxBQAAQBAJ",
       "etag":"yythSs+ukDQ",
       "selfLink":"https://www.googleapis.com/books/v1/volumes/2kjxBQAAQBAJ",
       "volumeInfo":{
          "title":"Systems Analysis and Design",
          "authors":[
             "Alan Dennis",
             "Barbara Haley Wixom",
             "Roberta M. Roth"
          ],
          "publisher":"John Wiley & Sons",
          "publishedDate":"2014-11-11",
          "description":"The 6th Edition of Systems Analysis and Design continues to offer a hands-on approach to SAD while",
          "industryIdentifiers":[
             {
                "type":"ISBN_13",
                "identifier":"9781118897843"
             },
             {
                "type":"ISBN_10",
                "identifier":"1118897846"
             }
          ],
          "pageCount":448,
          "dimensions": {
           "height": "25.20 cm",
           "width": "20.20 cm",
           "thickness": "1.40 cm"
          },
          "printType":"BOOK",
          "categories":[
             "Computers"
          ],
          "averageRating":4.5,
          "ratingsCount":3,
          "maturityRating":"NOT_MATURE",
          "allowAnonLogging":false,
          "contentVersion":"0.2.0.0.preview.1",
          "imageLinks":{
             "smallThumbnail":"http://books.google.com.au/books/content?id=2kjxBQAAQBAJ&printsec=frontcover&img=1&zoom=5&edge=curl&source=gbs_api",
             "thumbnail":"http://books.google.com.au/books/content?id=2kjxBQAAQBAJ&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api"
          },
          "language":"en",
          "previewLink":"http://books.google.com.au/books?id=2kjxBQAAQBAJ&printsec=frontcover&dq=systems+analysis+and+design&hl=&cd=1&source=gbs_api",
          "infoLink":"http://books.google.com.au/books?id=2kjxBQAAQBAJ&dq=systems+analysis+and+design&hl=&source=gbs_api",
          "canonicalVolumeLink":"http://books.google.com.au/books/about/Systems_Analysis_and_Design.html?hl=&id=2kjxBQAAQBAJ"
       },
       "saleInfo":{
          "country":"AU",
          "saleability":"NOT_FOR_SALE",
          "isEbook":false,
          "listPrice": {
            "amount": 11.99,
            "currencyCode": "USD"
          },
          "retailPrice": {
            "amount": 11.99,
            "currencyCode": "USD"
          },
          "buyLink": "https://books.google.com/books?id=zyTCAlFPjgYC&ie=ISO-8859-1&buy=&source=gbs_api"
       },
       "accessInfo":{
          "country":"AU",
          "viewability":"PARTIAL",
          "embeddable":true,
          "publicDomain":false,
          "textToSpeechPermission":"ALLOWED",
          "epub":{
             "isAvailable":false
          },
          "pdf":{
             "isAvailable":false
          },
          "webReaderLink":"http://books.google.com.au/books/reader?id=2kjxBQAAQBAJ&hl=&printsec=frontcover&output=reader&source=gbs_api",
          "accessViewStatus":"SAMPLE",
          "quoteSharingAllowed":false
       }
    }
    ';

    public function setup()
    {
        /** @var AnnotationMapper mapper */
        $this->mapper = new AnnotationMapper();
    }

    public function testVolumeEntityMapping()
    {
        $json_obj = json_decode($this->book_volume, true);
        $object   = $this->mapper->resolveEntity($json_obj['kind']);
        unset($json_obj['kind']);

        // Get the mapped volume object
        $volume = $this->mapper->map($object, $json_obj);

        foreach ($json_obj as $key => $value) {
            $actual = call_user_func([$volume, 'get' . ucfirst($key)]);

            if (is_array($json_obj[$key])) {
                continue;
            }

            $this->assertEquals($json_obj[$key], $actual);
        }
    }

    public function testVolumeInfoEntityMapping()
    {
        $json_obj = json_decode($this->book_volume, true);
        $object   = $this->mapper->resolveEntity($json_obj['kind']);

        // Get the mapped volume object
        $volume = $this->mapper->map($object, $json_obj);

        foreach ($json_obj['volumeInfo'] as $key => $value) {
            if (!is_callable([$volume->getVolumeInfo(), 'get' . ucfirst($key)])) {
                continue;
            }

            $actual = call_user_func([$volume->getVolumeInfo(), 'get' . ucfirst($key)]);

            if (is_array($json_obj['volumeInfo'][$key]) && !is_array($actual)) {

                switch (get_class($actual)) {
                    case 'Nerdstorm\GoogleBooks\Entity\VolumeDimensions':
                        $this->assertEquals((float) $json_obj['volumeInfo'][$key]['width'], $actual->getWidth());
                        $this->assertEquals((float) $json_obj['volumeInfo'][$key]['height'], $actual->getHeight());
                        $this->assertEquals(
                            (float) $json_obj['volumeInfo'][$key]['thickness'], $actual->getThickness()
                        );
                        break;

                    case 'Nerdstorm\GoogleBooks\Entity\VolumeImageLinks':
                        $this->assertEquals(
                            $json_obj['volumeInfo'][$key]['smallThumbnail'], $actual->getSmallThumbnail()
                        );
                        $this->assertEquals($json_obj['volumeInfo'][$key]['thumbnail'], $actual->getThumbnail());
                        break;
                }

            } elseif ($actual instanceof \DateTime) {
                $this->assertEquals(new \DateTime($json_obj['volumeInfo'][$key]), $actual);
            } else {
                $this->assertEquals($json_obj['volumeInfo'][$key], $actual);
            }
        }
    }

    public function testSaleInfoEntityMapping()
    {
        $json_obj = json_decode($this->book_volume, true);
        $object   = $this->mapper->resolveEntity($json_obj['kind']);

        // Get the mapped volume object
        $volume = $this->mapper->map($object, $json_obj);

        foreach ($json_obj['saleInfo'] as $key => $value) {
            if (!is_callable([$volume->getSaleInfo(), 'get' . ucfirst($key)])) {
                continue;
            }

            $actual = call_user_func([$volume->getSaleInfo(), 'get' . ucfirst($key)]);

            if ($actual instanceof \DateTime) {
                $this->assertEquals(new \DateTime($json_obj['saleInfo'][$key]), $actual);
            } elseif ($actual instanceof BookPrice) {
                $this->assertEquals($json_obj['saleInfo'][$key]['amount'], $actual->getAmount());
                $this->assertEquals($json_obj['saleInfo'][$key]['currencyCode'], $actual->getCurrencyCode());
            } else {
                $this->assertEquals($json_obj['saleInfo'][$key], $actual);
            }
        }
    }

    public function testAccessInfoEntityMapping()
    {
        $json_obj = json_decode($this->book_volume, true);
        /** @var Volume $object */
        $object      = $this->mapper->resolveEntity($json_obj['kind']);
        $access_info = $json_obj['accessInfo'];

        // Get the mapped volume object
        $volume = $this->mapper->map($object, $json_obj);

        foreach ($access_info as $key => $value) {
            if (!is_callable([$volume->getAccessInfo(), 'get' . ucfirst($key)])) {
                continue;
            }

            $actual = call_user_func([$volume->getAccessInfo(), 'get' . ucfirst($key)]);
            $this->assertEquals($value, $actual);
        }
    }

}