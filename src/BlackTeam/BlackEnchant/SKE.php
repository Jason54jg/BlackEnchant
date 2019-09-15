<?php

namespace BlackTeam\BlackEnchant;

use pocketmine\{
    plugin\Plugin, Server, Player
};
use pocketmine\event\Listener;
use pocketmine\event\block\{SignChangeEvent, BlockBreakEvent};
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\tile\Sign;
use pocketmine\item\Armor;
use pocketmine\plugin\PluginBase;
use pocketmine\item\enchantment\{EnchantmentInstance, Enchantment};
use onebone\economyapi\EconomyAPI;
use pocketmine\item\Sword;
use pocketmine\item\Tool;

class SKE extends PluginBase implements Listener{


    public const ES_SIGN_HEADER = "[BP]";
    public const SIGN_PREFIX = "§9[§eEnchant§9]";
	
	public const PREFIX = "§9[§eEnchant§9]§r ";
	
	/* @var $eapi EconomyAPI */
	public $eapi;
	
	public function onEnable(){
      $foundEconomy = false;
      while(!$foundEconomy) {
          $eapi = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
          if(!$foundEconomy and $eapi instanceof EconomyAPI) {
              $this->eapi = EconomyAPI::getInstance();
              $foundEconomy = true;
              break;
          }
          $eapi = $this->getServer()->getPluginManager()->getPlugin("PocketMoney");
          if(!$foundEconomy and $eapi instanceof Plugin) {
              $this->eapi = $eapi;
              $foundEconomy = true;
              break;
          }
          $eapi = $this->getServer()->getPluginManager()->getPlugin("MassiveEconomy");
          if(!$foundEconomy and $eapi instanceof Plugin) {
              $this->eapi = $eapi;
              $foundEconomy = true;
              break;
          }
      }
      $this->getServer()->getPluginManager()->registerEvents($this, $this);

    }
    /* @param string|int
         @return float|int
    */
    public function isStringNumeric(string $param){
        if(is_numeric($param)){
            return $param + 0;
        }
        return 0;
    }
    
    public function onSignChange(SignChangeEvent $ev){
    	$player = $ev->getPlayer();
	if($player->isOp()){
            if($ev->getLine(0) == self::ES_SIGN_HEADER){
        	if(is_numeric(self::isStringNumeric($ev->getLine(1)))){
        	    if(is_numeric(self::isStringNumeric($ev->getLine(3)))){
                    $ev->setLine(0, self::SIGN_PREFIX);
                    $ev->setLine(3, "§ePrix:§9 " . $ev->getLine(3));
                    $ev->setLine(2, "§eNiveau:§9 " . $ev->getLine(2));
                    $ev->setLine(1, "§e" . $ev->getLine(1));
                    }else{
                	$player->sendMessage(self::PREFIX . "§4Le niveau que vous avez spécifié n'est pas numérique!");
                    }
                }else{
                    $player->sendMessage(self::PREFIX . "§4Le coût que vous avez spécifié n'est pas numérique!");
                }
	    }
        }
    }
    
    public function onInteract(PlayerInteractEvent $ev){
    	$player = $ev->getPlayer();
        $block = $ev->getBlock();
        $tile = $block->getLevel()->getTile($block);
        if($tile instanceof Sign){
        	if($tile->getLine(0) == self::SIGN_PREFIX){
                $level = str_replace("§eNiveau:§9 ", "", $tile->getLine(2));
                $enchantment = str_replace("§e", "", $tile->getLine(1));
                $cost = str_replace("§ePrix:§9 ", "", $tile->getLine(3));
                switch(strtolower($enchantment)){
                	case "tranchant":
                        if($player->getInventory()->getItemInHand() instanceof Sword){
                        	if($this->eapi->myMoney($player->getName()) > $cost){
                        	    $this->eapi->reduceMoney($player->getName(), $cost, true);
                              $player->sendMessage(self::PREFIX . "§9Tu as été débité de " . "§e" . $cost . "§9€");
                              $enchid = Enchantment::getEnchantment(9);
                              $ench =  new EnchantmentInstance($enchid, $level + 0);
                              $i = clone $player->getInventory()->getItemInHand();
                        	    $i->addEnchantment($ench);
                              $player->getInventory()->setItemInHand($i);
                            }else{
                            	$player->sendMessage(self::PREFIX . "§cTu n'as pas assez d'argent pour cette enchantement !");
                            }
                        }else{
                        	$player->sendMessage(self::PREFIX . "§cL'objet que tu as dans la main n'est pas une épée !");
                        }
                        break;
                	case "smite":
                        if($player->getInventory()->getItemInHand() instanceof Sword){
                        	if($this->eapi->myMoney($player->getName()) > $cost){
                        	    $this->eapi->reduceMoney($player->getName(), $cost, true);
                              $player->sendMessage(self::PREFIX . "§9Tu as été débité de " . "§e" . $cost . "§9€");
                              $enchid = Enchantment::getEnchantment(10);
                              $ench =  new EnchantmentInstance($enchid, $level + 0);
                              $i = clone $player->getInventory()->getItemInHand();
                        	    $i->addEnchantment($ench);
                              $player->getInventory()->setItemInHand($i);
                            }else{
                            	$player->sendMessage(self::PREFIX . "§cTu n'as pas assez d'argent pour cette enchantement !");
                            }
                        }else{
                        	$player->sendMessage(self::PREFIX . "§cL'objet que tu as dans la main n'est pas une épée !");
                        }
                        break;
                	case "bane_of_arthropods":
                        if($player->getInventory()->getItemInHand() instanceof Sword){
                        	if($this->eapi->myMoney($player->getName()) > $cost){
                        	    $this->eapi->reduceMoney($player->getName(), $cost, true);
                              $player->sendMessage(self::PREFIX . "§bTu as été débité de " . "§f" . $cost . "§b€");
                              $enchid = Enchantment::getEnchantment(11);
                              $ench =  new EnchantmentInstance($enchid, $level + 0);
                              $i = clone $player->getInventory()->getItemInHand();
                        	    $i->addEnchantment($ench);
                              $player->getInventory()->setItemInHand($i);
                            }else{
                            	$player->sendMessage(self::PREFIX . "§cTu n'as pas assez d'argent pour cette enchantement !");
                            }
                        }else{
                        	$player->sendMessage(self::PREFIX . "§cL'objet que tu as dans la main n'est pas une épée !");
                        }
                        break;
                	case "knockback":
                        if($player->getInventory()->getItemInHand() instanceof Sword){
                        	if($this->eapi->myMoney($player->getName()) > $cost){
                        	    $this->eapi->reduceMoney($player->getName(), $cost, true);
                              $player->sendMessage(self::PREFIX . "§bTu as été débité de " . "§f" . $cost . "§b€");
                              $enchid = Enchantment::getEnchantment(12);
                              $ench =  new EnchantmentInstance($enchid, $level + 0);
                              $i = clone $player->getInventory()->getItemInHand();
                        	    $i->addEnchantment($ench);
                              $player->getInventory()->setItemInHand($i);
                            }else{
                            	$player->sendMessage(self::PREFIX . "§cTu n'as pas assez d'argent pour cette enchantement !");
                            }
                        }else{
                        	$player->sendMessage(self::PREFIX . "§cL'objet que tu as dans la main n'est pas une épée !");
                        }
                        break;
                	case "aura_de_feu":
                        if($player->getInventory()->getItemInHand() instanceof Sword){
                        	if($this->eapi->myMoney($player->getName()) > $cost){
                        	    $this->eapi->reduceMoney($player->getName(), $cost, true);
                              $player->sendMessage(self::PREFIX . "§bTu as été débité de " . "§f" . $cost . "§b€");
                              $enchid = Enchantment::getEnchantment(13);
                              $ench =  new EnchantmentInstance($enchid, $level + 0);
                              $i = clone $player->getInventory()->getItemInHand();
                        	    $i->addEnchantment($ench);
                              $player->getInventory()->setItemInHand($i);
                            }else{
                            	$player->sendMessage(self::PREFIX . "§cTu n'as pas assez d'argent pour cette enchantement !");
                            }
                        }else{
                        	$player->sendMessage(self::PREFIX . "§cL'objet que tu as dans la main n'est pas une épée !");
                        }
                        break;
                	case "butin":
                        if($player->getInventory()->getItemInHand() instanceof Sword){
                        	if($this->eapi->myMoney($player->getName()) > $cost){
                        	    $this->eapi->reduceMoney($player->getName(), $cost, true);
                              $player->sendMessage(self::PREFIX . "§bTu as été débité de " . "§f" . $cost . "§b€");
                              $enchid = Enchantment::getEnchantment(14);
                              $ench =  new EnchantmentInstance($enchid, $level + 0);
                              $i = clone $player->getInventory()->getItemInHand();
                        	    $i->addEnchantment($ench);
                              $player->getInventory()->setItemInHand($i);
                            }else{
                            	$player->sendMessage(self::PREFIX . "§cTu n'as pas assez d'argent pour cette enchantement !");
                            }
                        }else{
                        	$player->sendMessage(self::PREFIX . "§cL'objet que tu as dans la main n'est pas une épée !");
                        }
                        break;
                	case "solidité":
                      if(($player->getInventory()->getItemInHand() instanceof Sword) || ($player->getInventory()->getItemInHand() instanceof Armor) || ($player->getInventory()->getItemInHand() instanceof Tool)){
         
                        	if($this->eapi->myMoney($player->getName()) > $cost){
                        	    $this->eapi->reduceMoney($player->getName(), $cost, true);
                              $player->sendMessage(self::PREFIX . "§bTu as été débité de " . "§f" . $cost . "§b€");
                              $enchid = Enchantment::getEnchantment(17);
                              $ench =  new EnchantmentInstance($enchid, $level + 0);
                              $i = clone $player->getInventory()->getItemInHand();
                        	    $i->addEnchantment($ench);
                              $player->getInventory()->setItemInHand($i);
                            }else{
                            	$player->sendMessage(self::PREFIX . "§cTu n'as pas assez d'argent pour cette enchantement !");
                            }
                        }else{
$player->sendMessage(self::PREFIX . "§cL'objet que tu as dans la main n'est pas une épée , une armure ou un outil !");
                        }
                        break;
                	case "protection":
                        if($player->getInventory()->getItemInHand() instanceof Armor){
                        	if($this->eapi->myMoney($player->getName()) > $cost){
                        	    $this->eapi->reduceMoney($player->getName(), $cost, true);
                              $player->sendMessage(self::PREFIX . "§bTu as été débité de " . "§f" . $cost . "§b€");
                              $enchid = Enchantment::getEnchantment(0);
                              $ench =  new EnchantmentInstance($enchid, $level + 0);
                              $i = clone $player->getInventory()->getItemInHand();
                        	    $i->addEnchantment($ench);
                              $player->getInventory()->setItemInHand($i);
                            }else{
                            	$player->sendMessage(self::PREFIX . "§cTu n'as pas assez d'argent pour cette enchantement !");
                            }
                        }else{
$player->sendMessage(self::PREFIX . "§cL'objet que tu as dans la main n'est pas une armure !");
                        }
                        break;
			case "efficacité":
                        if($player->getInventory()->getItemInHand() instanceof Tool){
                        	if($this->eapi->myMoney($player->getName()) > $cost){
                        	    $this->eapi->reduceMoney($player->getName(), $cost, true);
                              $player->sendMessage(self::PREFIX . "§bTu as été débité de " . "§f" . $cost . "§b€");
                              $enchid = Enchantment::getEnchantment(15);
                              $ench =  new EnchantmentInstance($enchid, $level + 0);
                              $i = clone $player->getInventory()->getItemInHand();
                        	    $i->addEnchantment($ench);
                              $player->getInventory()->setItemInHand($i);
                            }else{
                            	$player->sendMessage(self::PREFIX . "§cTu n'as pas assez d'argent pour cette enchantement !");
                            }
                        }else{
                        	$player->sendMessage(self::PREFIX . "§cL'objet que tu as dans la main n'est pas un outil !");
                        }
                        break;
			case "respiration":
                        if($player->getInventory()->getItemInHand()->isSword()){
                        	if($this->eapi->myMoney($player->getName()) > $cost){
                        	    $this->eapi->reduceMoney($player->getName(), $cost, true);
                              $player->sendMessage(self::PREFIX . "§bTu as été débité de " . "§f" . $cost . "§b€");
                              $enchid = Enchantment::getEnchantment(102);
                              $ench =  new EnchantmentInstance($enchid, $level + 0);
                              $i = clone $player->getInventory()->getItemInHand();
                        	    $i->addEnchantment($ench);
                              $player->getInventory()->setItemInHand($i);
                            }else{
                            	$player->sendMessage(self::PREFIX . "§cTu n'as pas assez d'argent pour cette enchantement !");
                            }
                        }else{
                        	$player->sendMessage(self::PREFIX . "§cL'objet que tu as dans la main n'est pas un casque !");
                        }
                        break;
			case "fortune":
                        if($player->getInventory()->getItemInHand() instanceof Tool){
                        	if($this->eapi->myMoney($player->getName()) > $cost){
                        	    $this->eapi->reduceMoney($player->getName(), $cost, true);
                              $player->sendMessage(self::PREFIX . "§bTu as été débité de " . "§f" . $cost . "§b€");
                              $enchid = Enchantment::getEnchantment(18);
                              $ench =  new EnchantmentInstance($enchid, $level + 0);
                              $i = clone $player->getInventory()->getItemInHand();
                        	    $i->addEnchantment($ench);
                              $player->getInventory()->setItemInHand($i);
                            }else{
                            	$player->sendMessage(self::PREFIX . "§cTu n'as pas assez d'argent pour cette enchantement !");
                            }
                        }else{
                        	$player->sendMessage(self::PREFIX . "§cL'objet que tu as dans la main n'est pas un outil !");
                        }
                        break;
			case "délicatesse":
                        if($player->getInventory()->getItemInHand() instanceof Tool){
                        	if($this->eapi->myMoney($player->getName()) > $cost){
                        	    $this->eapi->reduceMoney($player->getName(), $cost, true);
                              $player->sendMessage(self::PREFIX . "§bTu as été débité de " . "§f" . $cost . "§b€");
                              $enchid = Enchantment::getEnchantment(16);
                              $ench =  new EnchantmentInstance($enchid, $level + 0);
                              $i = clone $player->getInventory()->getItemInHand();
                        	    $i->addEnchantment($ench);
                              $player->getInventory()->setItemInHand($i);
                            }else{
                            	$player->sendMessage(self::PREFIX . "§cTu n'as pas assez d'argent pour cette enchantement !");
                            }
                        }else{
                        	$player->sendMessage(self::PREFIX . "§cL'objet que tu as dans la main n'est pas un outil !");
                        }
                        break;
			case "chute_amortie":
                        if($player->getInventory()->getItemInHand() instanceof Armor){
                        	if($this->eapi->myMoney($player->getName()) > $cost){
                        	    $this->eapi->reduceMoney($player->getName(), $cost, true);
                              $player->sendMessage(self::PREFIX . "§bTu as été débité de " . "§f" . $cost . "§b€");
                              $enchid = Enchantment::getEnchantment(2);
                              $ench =  new EnchantmentInstance($enchid, $level + 0);
                              $i = clone $player->getInventory()->getItemInHand();
                        	    $i->addEnchantment($ench);
                              $player->getInventory()->setItemInHand($i);
                            }else{
                            	$player->sendMessage(self::PREFIX . "§cTu n'as pas assez d'argent pour cette enchantement !");
                            }
                        }else{
                        	$player->sendMessage(self::PREFIX . "§cL'objet que tu as dans la main n'est pas des bottes !");
                        }
                        break;

				
                }
            }
        }
    }
}
