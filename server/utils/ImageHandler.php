<?php
    namespace app\utils;

    class ImageHandler{

        private $base_url = "../assets/uploads/";
        private $valid_extension = array("png","jpeg","jpg");
        private $dest_url = null;
        private $set = false;

        function __construct($file_tmp, $file_name, $user_id, $product_name, $type = "deposit")
        {      
            $this->base_url = $this->base_url.$type."/".$user_id;

            if(!file_exists($this->base_url)){
                mkdir($this->base_url, 0777, true);
            }
            $this->imageProcessor($file_tmp, $file_name, strtolower($product_name));
        }


        function getImageURI(){ return $this->dest_url; }
        function isSet(){ return $this->set; }

        // private function
        private function getImageExtension($image_file){
            return strtolower( pathinfo($image_file, PATHINFO_EXTENSION) );
        }

        private function imageProcessor($tmp, $file, $product_name): void{
            // rewrite image name
            $file_extension = $this->getImageExtension($file);
            $target_file = $this->base_url."/".$product_name.".".$file_extension;

            // if(file_exists($target_file) == false){
                if(in_array($file_extension, $this->valid_extension)) {
                    if(move_uploaded_file($tmp, $target_file)) {
                        $this->dest_url = $target_file;
                        $this->set = true;
                    }
                }
            // }else $this->set = "exist";
        }

    }
      
?>