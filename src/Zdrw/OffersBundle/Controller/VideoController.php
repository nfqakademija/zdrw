<?php

namespace Zdrw\OffersBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Vimeo\Vimeo;
use Vimeo\Exceptions\VimeoUploadException;
use Symfony\Component\HttpFoundation\Response;

class VideoController extends Controller
{
    public function indexAction()
    {

        $offerId = $_POST['id'];
        $userid = $_POST['userid'];

        $fileName = $_FILES["file1"]["name"]; // The file name
        $ext = pathinfo($fileName, PATHINFO_EXTENSION);
        $fileName = $offerId.".".$ext;

        $manager = $this->getDoctrine()->getManager();
        $offer = $manager->getRepository('ZdrwOffersBundle:Offer')->findOneById($offerId);
        $offer->setStatus(2);
        $offer->setParticipantId($userid);
        $offer->setVideo($fileName);
        $manager->flush();

        $fileTmpLoc = $_FILES["file1"]["tmp_name"]; // File in the PHP tmp folder
        /*$fileType = $_FILES["file1"]["type"]; // The type of file it is
        $fileSize = $_FILES["file1"]["size"]; // File size in bytes
        $fileErrorMsg = $_FILES["file1"]["error"]; // 0 for false... and 1 for true*/
        if (!$fileTmpLoc) { // if file not chosen
            $ret = "ERROR: Please browse for a file before clicking the upload button.";
        } else {
            if (move_uploaded_file($fileTmpLoc, "uploads/$fileName")) {
                $ret = "Upload is complete";
            } else {
                $ret = "move_uploaded_file function failed";
            }
        }
        return new Response($ret);
        /*var_dump($_POST);
        $form = $this->createFormBuilder()
            ->add('video', 'file') // If I remove this line data is submitted correctly
            ->getForm();

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            $file = $form['video']->getData();
            //$file = $file->getPathname();
            $name = $file->getClientOriginalName();
            $file->move("uploads",$name);
        }
        //return $this->render('ZdrwOffersBundle:Default:upload.html.twig', array('form' =>$form->createView(), 'user'=> $this->getUser()));*/
    }

    public function toVimeoAction($file)
    {

        $config = array();
        $config['client_id'] = $this->container->getParameter("vimeo_client_id");
        $config['client_secret'] = $this->container->getParameter("vimeo_client_secret");
        $config['access_token'] = $this->container->getParameter("vimeo_access_token");
        if (empty($config['access_token'])) {
            throw new Exception('You can not upload a file without an access token. You can find this token on your app page, or generate one using auth.php');
        }
        $file = "C:/wamp/www/zdrw/web/uploads/".$file;

        //include $file;
        $lib = new Vimeo($config['client_id'], $config['client_secret'], $config['access_token']);
        // Get the args from the command line to see what files to upload.
        $files[] = $file;

        // Keep track of what we have uploaded.
        $uploaded = array();
        $link = '';
        // Send the files to the upload script.
        foreach ($files as $file_name) {
            // Update progress.
            print 'Uploading ' . $file_name . "\n";
            try {
                // Send this to the API library.
                $uri = $lib->upload($file_name);
                // Now that we know where it is in the API, let's get the info about it so we can find the link.
                $video_data = $lib->request($uri);
                // Pull the link out of successful data responses.
                if($video_data['status'] == 200) {
                    $link = $video_data['body']['link'];
                }
                // Store this in our array of complete videos.
                $uploaded[] = array('file' => $file_name, 'api_video_uri' => $uri, 'link' => $link);
            }
            catch (VimeoUploadException $e) {
                // We may have had an error. We can't resolve it here necessarily, so report it to the user.
                print 'Error uploading ' . $file_name . "\n";
                print 'Server reported: ' . $e->getMessage() . "\n";
            }
        }
        // Provide a summary on completion with links to the videos on the site.
        print 'Uploaded ' . count($uploaded) . " files.\n\n";
        foreach ($uploaded as $site_video) {
            extract($site_video);
            print "$file is at $link.\n";
        }

        $form = $this->createFormBuilder()
            ->add('video', 'file') // If I remove this line data is submitted correctly
            ->getForm();
        $infoProvider = $this->get('user_info_provider');
        $pass = $infoProvider->userInfo($this->getUser());
        $pass['form'] = $form->createView();
        return $this->render('ZdrwOffersBundle:Default:upload.html.twig', $pass);
    }
}