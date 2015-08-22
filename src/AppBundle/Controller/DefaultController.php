<?php

namespace AppBundle\Controller;

use AppBundle\Document\Node;
use AppBundle\Document\Upload;
use Doctrine\ODM\MongoDB\DocumentRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
//        $product = new Node();
//        $product->setTitle('A Foo Bar');
//
//        $dm = $this->get('doctrine_mongodb')->getManager();
////        $dm->persist($product);
////        $dm->flush();
//
//        var_dump($product->getId());
//
//        $file = new Upload();
//        $file->setFile('/data/tumblr_nqsx6hiJYq1qzy9ouo1_1280.jpg');
//        $file->setFilename('tumblr_nqsx6hiJYq1qzy9ouo1_1280.jpg');
//        $file->setMimeType('image/jpeg');
//
////        $dm = $this->get('doctrine.odm.mongodb.document_manager');
////        $dm->persist($file);
////        $dm->flush();
//
//        var_dump($file->getId());

        // replace this example code with whatever you need
        return $this->render(
          'CowtentApplicationBundle:Default:index.html.twig',
          array(
//            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
          )
        );
    }

    /**
     * @Route("/add", name="node_add")
     */
    public function addAction()
    {
        $dm = $this->get('doctrine_mongodb')->getManager();

        $fields = array(
          array('image' => 'image 1', 'content' => 'content right'),
          array('content' => 'content left', 'image' => 'image 2'),
        );

        $node = new Node();
        $node->setTitle('Title');
        $node->setContent($fields);
        $dm->persist($node);
        $dm->flush();

        $id = $node->getId();

//        $node->setTitle('Title 2');
//        $dm->persist($node);
//        $dm->flush();

        var_dump($id);

        // replace this example code with whatever you need
        return $this->render(
          'default/index.html.twig',
          array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
          )
        );
    }

    /**
     * @Route("/node/{id}/view", name="node_view")
     */
    public function viewAction($id)
    {
        /** @var DocumentRepository $repository */
        $repository = $this->get('doctrine_mongodb')->getRepository('AppBundle:Node');

        /** @var Node $node */
        $node = $repository->find($id);

        if (null === $node) {
            throw $this->createNotFoundException(sprintf('Node with id "%s" could not be found', $id));
        }

        var_dump($node);

        $fields = $node->getContent();

        var_dump($fields);

        /** @var \MongoId $image */
        $reference = $fields[0];

//        $repositoryImage = $this->get('doctrine_mongodb')->getRepository($reference['type']);
//        $image           = $repositoryImage->find($reference['object']);
//
//        var_dump($image);

        // replace this example code with whatever you need
        return $this->render(
          'default/index.html.twig',
          array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
          )
        );
    }

    /**
     * @Route("/node/{id}/delete", name="node_delete")
     */
    public function deleteAction($id)
    {
        /** @var DocumentRepository $repository */
        $repository = $this->get('doctrine_mongodb')->getRepository('AppBundle:Node');

        /** @var Node $node */
        $node = $repository->find($id);

        if (null === $node) {
            throw $this->createNotFoundException(sprintf('Node with id "%s" could not be found', $id));
        }

        $dm = $this->get('doctrine_mongodb')->getManager();
        $dm->remove($node);
        $dm->flush();

        $url      = $this->generateUrl('homepage');
        $response = new RedirectResponse($url);

        return $response;
    }

    /**
     * @Route("/upload/{id}", name="upload_show")
     */
    public function uploadShowAction($id)
    {
        /** @var Upload $upload */
        $upload = $this->get('doctrine_mongodb')
          ->getRepository('AppBundle:Upload')
          ->find($id);

        if (null === $upload) {
            throw $this->createNotFoundException(sprintf('Upload with id "%s" could not be found', $id));
        }

        $response = new Response();
        $response->headers->set('Content-Type', $upload->getMimeType());
        $response->setContent($upload->getFile()->getBytes());

        return $response;
    }
}
