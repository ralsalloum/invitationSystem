<?php

namespace App\Controller;

use App\Document\Product;
use Doctrine\ODM\MongoDB\DocumentManager;
use OpenApi\Examples\Misc\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("v1/product/")
 */
class ProductController extends BaseController
{
    private DocumentManager $documentManager;

    public function __construct(SerializerInterface $serializer, DocumentManager $documentManager)
    {
        parent::__construct($serializer);
        $this->documentManager = $documentManager;
    }

     /**
      * Create a new invitation by user.
      * @Route("newproduct", name="createNewProduct", methods={"POST"})
      *
      */
     public function createNewDocument(): JsonResponse
     {
         $product = new Product();
         $product->setName('A Foo Bar');
         $product->setPrice('19.99');

         $this->documentManager->persist($product);
         $this->documentManager->flush();

         return new JsonResponse(["product"=> $product->getId()]);
     }
}
