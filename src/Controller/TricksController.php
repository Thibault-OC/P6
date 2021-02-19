<?php

namespace App\Controller;

use App\Entity\Tricks;
use App\Entity\Video;
use App\Entity\Image;
use App\Entity\Comment;
use App\Form\TricksType;
use App\Repository\TricksRepository;
use App\Repository\ImageRepository;
use App\Repository\VideoRepository;
use App\Service\FileUploader;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Cocur\Slugify\Slugify;



/**
 * @Route("/")
 */
class TricksController extends AbstractController
{
    /**
     * @Route("/", name="tricks_index", methods={"GET"})
     */

    /* Route page Home */
    public function index( TricksRepository $tricksRepository ): Response
    {
        $em = $this->getDoctrine()->getManager();


        /* Calcul du nombre de trick pour la pagination ajax */

        $repoArticles = $em->getRepository(Tricks::class);

        $totalArticles = $repoArticles->createQueryBuilder('a')
            ->select('count(a.id)')
            ->getQuery()
            ->getSingleScalarResult();



        /* retourne la vue home avec le nombre des tricks */

        return $this->render('tricks/index.html.twig', [
            'tricks' => $tricksRepository->findBy(
                array(), array('id' => 'DESC'),
                10

            ),
            'nbTricks' => $totalArticles,



        ]);
    }

    /**
     * @Route("/new", name="tricks_new", methods={"GET","POST"})
     */

    /* Route pas création d'un trick */
    public function new(Request $request , FileUploader $fileUploader ,TricksRepository $tricksRepository)
    {
        
        $trick = new Tricks();

        $slugify = new Slugify();

        /*Ajout date de création du Trick avec la date actuel*/

        $trick->setCreatedAt(new \DateTime());

        $form = $this->createForm(TricksType::class, $trick);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $trick->setUser($this->getUser());

            /* Appel de la fonction slugify pour modifier le titre */

            $slug = $slugify->slugify($form->get('title')->getData(), ['separator' => '-']);

            $trick->setSlug($slug);

            $name_unique = $tricksRepository->findBy(
                ['title' => $form->get('title')->getData()]
            );

            $content = $form->get('content')->getData();

            /* utilisation de nlb2r() pour garder les sauts de ligne du contenu */

            $content_form = nl2br( $content );

            $trick->setContent($content_form);

            /**
             * @var UploadedFile $videos
             */

            /* boucle d'insertion du champ video */

            $videos = $form->get('videos')->getData();

            foreach ($videos as $video){

                $trickVideos = new Video();

                /* renome une url youtube en lien embed pour faciliter l'ajout des videos */

                $video->filename = str_replace("watch?v=","embed/", $video->filename );


                $trickVideos->setFilename($video->getFilename());



            }

            $images= $form->get('images')->getData();

            /* boucle d'insertion du champ image */
            foreach ($images as $image){

                $filename = $fileUploader->upload($image);

                $trickImages = new Image();

                $trickImages->setFilename($filename);

                $trick->addImages($trickImages);


            }


            /**
             * @var UploadedFile $imageFile
             */

            /* insertion de l'image principal du trick */

            $imageFile = $form->get('image')->getData();


            if ($imageFile) {


                $image= $fileUploader->upload($imageFile);

                $trick->setImage($image);

            }


            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($trick);

            /* verification avant insertion : le nom du trick dooit etre unique */

            if (empty($name_unique)){
                $entityManager->flush();
                $this->addFlash('success', 'Article Created!');

                return $this->redirectToRoute('tricks_index');
            }

            else{
                $this->addFlash('warning', 'This name trick already exists');
            }

        }

        return $this->render('tricks/new.html.twig', [
            'trick' => $trick,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{slug}", name="tricks_show",  methods={"GET","POST"})
     */

    /* route d'affichage d'un trick */
    public function show(Tricks $trick ,CommentRepository $commentRepository, Request $request ): Response
    {
        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        $comment->setCreatedAt(new \DateTime());

        $user = $this->getUser();

        $comment->setUser($user);

        $trickComment= $comment->setTrick($trick);

        $comment->getTrick($trickComment);

        /* Vérification du contenu des commentaires */

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('tricks_show', [
                'slug' => $trick->getSlug(),
        ]);
        }

        /* retourne a la vue show avec un nombre de commentaire */

        return $this->render('tricks/show.html.twig', [
            'comments' => $commentRepository->findBy(
                ['trick' => $trick],
                ['created_at' => 'DESC'],
                5
            ),
            'nbComments' =>count($trick->getTrick()),
            'trick' => $trick,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{slug}/edit", name="tricks_edit", methods={"GET","POST"})
     */
    /* Route de la modification d'un trick */

    public function edit(Request $request, Tricks $trick ,  FileUploader $fileUploader , TricksRepository $tricksRepository): Response
    {

        $slugify = new Slugify();

        /* remplacement des balises <br> pour garder les sauts de lignes à la modification */

        $trick->content = str_replace("<br />", "", $trick->getContent("content"));

        $form = $this->createForm(TricksType::class, $trick);


        $trick->setUpdatedAt(new \DateTime());
        

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /* vérification du nom unique a la modification */

            $name_unique = $tricksRepository->findBy(
                ['title' => $form->get('title')->getData(),]
            );

            /* si le nom est changé on le slug de nouveau */

            $slug = $slugify->slugify($form->get('title')->getData(), ['separator' => '-']);

            $trick->setSlug($slug);


            $content = $form->get('content')->getData();

            $content_form = nl2br( $content );

            $trick->setContent($content_form);

            $imageFile = $form->get('image')->getData();


            if ($imageFile != null){

                $trick->setImage(

                    new File($this->getParameter('brochures_directory').'/'.$trick->getImage())

                );

                if ($imageFile) {


                    $image= $fileUploader->upload($imageFile);

                    $trick->setImage($image);

                }
            }

            $images= $form->get('images')->getData();

            if ($images){
                foreach ($images as $image){

                    $filename = $fileUploader->upload($image);

                    $trickImages = new Image();

                    $trickImages->setFilename($filename);

                    $trick->addImages($trickImages);


                }
            }



            /**
             * @var UploadedFile $videos
             */

            $videos = $form->get('videos')->getData();



            if($videos != null){

                foreach ($videos as $video){

                    $trickVideos = new Video();

                    $video->filename = str_replace("watch?v=","embed/", $video->filename );
                    
                    $trickVideos->setFilename($video->getFilename());

                }
            }


            if ($name_unique && $name_unique[0]->getId() == $trick->getId() ){
                $this->getDoctrine()->getManager()->flush();

                $this->addFlash('success', 'Article is updated !');

                return $this->redirectToRoute('tricks_index');
            }
            elseif (empty($name_unique)){
                $this->getDoctrine()->getManager()->flush();

                $this->addFlash('success', 'Article is updated !');

                return $this->redirectToRoute('tricks_index');
            }

            else{
                $this->addFlash('warning', 'This name trick already exists');
            }


        }

        return $this->render('tricks/edit.html.twig', [
            'trick' => $trick,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/tricks/deleteImage", name="tricks_delete_image", methods={"POST"})
     */

    /* supression ajax d'une image */

    public function deleteImage(Request $request , ImageRepository $imagerepository): Response
    {

        $id = $request->request->get('id');
        $em = $this->getDoctrine()->getManager();
        $evenement = $imagerepository->find($id);

        $em->remove($evenement);
        $em->flush();


        return new JsonResponse($id);

    }

    /**
     * @Route("/tricks/deleteVideo", name="tricks_delete_video", methods={"POST"})
     */
    /* supression ajax d'une video */

    public function deleteVideo(Request $request , VideoRepository $videorepository): Response
    {

        $id = $request->request->get('id');

        $em = $this->getDoctrine()->getManager();
        $evenement = $videorepository->find($id);


        $em->remove($evenement);
        $em->flush();

        return new JsonResponse($id);

    }

    /**
     * @Route("/{id}", name="tricks_delete", methods={"DELETE"})
     */

    /* supression d'un trick */

    public function delete(Request $request, Tricks $trick , FileUploader $fileUploader): Response
    {
        if($trick->getUser() == $this->getUser()) {
            if ($this->isCsrfTokenValid('delete'.$trick->getId(), $request->request->get('_token'))) {
                $entityManager = $this->getDoctrine()->getManager();

                $images = $trick->getImages();

                foreach ($images as $image) {

                    $image->getId();

                    /* à la supression du trick on supprime ces images du dossier upload */

                    $fileUploader->removeUpload($image);

                }

                $entityManager->remove($trick);
                $entityManager->flush();
            }
        }
        $this->addFlash('warning', 'This name trick already exists');

        return $this->redirectToRoute('tricks_index');
    }

  
}
