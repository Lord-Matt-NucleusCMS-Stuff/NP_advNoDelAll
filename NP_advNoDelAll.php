<?php
/**
 * Description of NP_advNoDelAll
 *
 * @author lordmatt
 */
class NP_advNoDelAll extends NucleusPlugin {
    
        protected $body=false;
        protected $debug_count=1;
        protected $disable_debug=true;
    
        public function getName() { return 'NP_advNoDelAll'; }
        public function getAuthor() { return 'lordmatt'; }
        public function getURL() { return ''; }
        public function getVersion() { return '1'; }
        public function getMinNucleusVersion() { return '330'; }
        public function getDescription() { return 'Removes the delete all option from Super Admin overview. Requires advAdmin 1.1 or better.'; }

        public function supportsFeature($what) {
            switch($what) {
                case 'SqlTablePrefix':
                    return 1;
                default:
                    return 0;
            }
        }
          
        public function getEventList() {
            return array('allAdminXML');
        }
        
        protected function debug($info){
            if($disable_debug){
                return 0;
            }
            if($this->body!==false){
                $cont   = $this->body->div[0]->div[1]->div[0];
                $newbox = $cont->prependChild('div', '');
                $newbox->addAttribute('style','border:3px solid #666;padding:8px;
                    background-color:#FFFFCC;font-size:90%;color:#333;');
                $newbox->addAttribute('id','advAdminBasicDemoBox');
                $newbox->addChild('p','DEBUG ['. $this->debug_count .']: (please ignore if not a developer)');
                $newbox->addChild('p',$info);
                $this->debug_count++;
            }
        }
        
        public function event_allAdminXML(&$data){
            if(isset($data['advVer']) && $data['advVer']>=1.1){
                $this->body = $data['PAGE']->body;
                if($data['action']=='overview'){
                    if(isset($this->body->div[0]->div[1]->div[0])){
                        $blogtableDIV = $this->body->div[0]->div[1]->div[0];
                        if(isset($blogtableDIV->table)){
                            $blogtable = $blogtableDIV->table;
                            foreach($blogtable->tbody->children() as $tr){
                                echo "<!--[Hi]-->";
                                if(isset($tr->td[7])){
                                    unset($tr->td[7]->a);
                                    $tr->td[7]->addChild('p','[Locked]');
                                }else{
                                    $this->debug('TD Not found like that on that row');
                                }
                            }
                        }else{
                            $this->debug('Not Valid table Ref');
                        }
                    }else{
                        $this->debug('Not Valid Path to table div');
                    }
                }else{   
                    return 1; // not needed
                }
            }else{
                return 0; //version 1.0 does not suppoert this
            }
        }
        
}

