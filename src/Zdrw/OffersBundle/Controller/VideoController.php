<?php

namespace Zdrw\OffersBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Zdrw\OffersBundle\Entity\Notification;

class VideoController extends Controller
{
    /**
     * Method to upload video from user
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $offerId = $_POST['id'];

        $user = $this->getUser();
        if ($user != null) {
            $fileName = $_FILES["file1"]["name"]; // The file name
            $ext = pathinfo($fileName, PATHINFO_EXTENSION);
            $fileName = $offerId . "." . $ext;

            $fileTmpLoc = $_FILES["file1"]["tmp_name"]; // File in the PHP tmp folder
            $filetype = $_FILES["file1"]["type"]; // The type of file it is

            if (!$fileTmpLoc) { // if file not chosen
                $ret = "ERROR: Please browse for a file before clicking the upload button.";
            } else {
                if (($filetype == "video/avi") || ($filetype == "video/mpeg") || ($filetype == "video/mpg")
                    || ($filetype == "video/mov") || ($filetype == "video/wmv") || ($filetype == "video/mp4")) {

                    if (move_uploaded_file($fileTmpLoc, "uploads/$fileName")) {
                        $path = $this->get('kernel')->getRootDir().'/../web/';
                        $video = $path."uploads/".$fileName;
                        $image = $path."uploads/thumb/".$offerId.".jpg";

                        $time =  exec("ffmpeg -i $video 2>&1 | grep 'Duration' | cut -d ' ' -f 4 | sed s/,//");

                        $duration = explode(":", $time);
                        $durationInSeconds = $duration[0]*3600 + $duration[1]*60+ round($duration[2]);
                        $durationMiddle = $durationInSeconds/2;

                        $minutes = $durationMiddle/60;
                        $realMinutes = floor($minutes);
                        $realSeconds = round(($minutes-$realMinutes)*60);

                        exec("ffmpeg -i $video -deinterlace -an -ss $realSeconds -f mjpeg -t 1 -r 1 -y -s 350x260 $image 2>&1");

                        $manager = $this->getDoctrine()->getManager();
                        $offer = $manager->getRepository('ZdrwOffersBundle:Offer')->findOneBy(array('id' => $offerId));
                        $offer->setParticipantId($user->getId());
                        $offer->setStatus(3);
                        $offer->setVideo($fileName);
                        $manager->flush();

                        $owner = $offer->getOwner();
                        $notification = new Notification();
                        $notification->setUser($owner);
                        $notification->setDate(new \DateTime("now"));
                        $url = $this->container->get('router')->generate(
                            'zdrw_dare',
                            array('id' => $offer->getId())
                        );
                        $notification->setNotification("User " . $user->getNickname() . " completed your challenge. You can view the video and approve <a href='" . $url . "'>here</a>");
                        $notification->setLink($url);
                        $manager->persist($notification);
                        $manager->flush();

                        $ret = "Upload is complete";
                    } else {
                        $ret = "move_uploaded_file function failed";
                    }
                } else {
                    $ret = "You have selected wrong format. Allowed: AVI, MPG, MOV, WMV, MP4";
                }
            }
        } else {
            $ret = "Please login";
        }
        return new Response($ret);
    }
}