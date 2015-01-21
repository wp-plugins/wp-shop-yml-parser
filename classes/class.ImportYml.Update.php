<?php

class ImportYml_Update {
	public function __construct($project_id) {		
		echo "<h2>Результаты обновления</h2><br/>";
		$project = new ImportYml_Project($project_id);
		$copyProject = ImportYml_Project::newProject("copy of {$project->getName()}",$project->getUrl());
		$copyProject->update();
		$this->compareProjects($project,$copyProject);
		$sourceFile = ImportYml_dir_ymls . "/{$copyProject->getFile()}";
		$destFile = ImportYml_dir_ymls . "/{$project->getFile()}";
		copy($sourceFile,$destFile);
		$copyProject->remove();
		$project->setChange();
	}

	public function compareProjects($project,$updatedProject) {
		global $wpdb;
		$offers = $project->getOffers();
		$old_id = $project->getID();
		$new_id = $updatedProject->getID();
		$updatedOffers = $updatedProject->getOffers();
		$a1 = array();
		$updNew = 0;
		$updPrice = 0;
		$updOn = 0;
		$updOff = 0;	
		$count = sizeOf($offers);
		for($i = 0; $i < $count; ++$i){
			//$updatedOffer = $this->getOffer($updatedOffers,$offer->id);
			$updatedOffer = $wpdb->get_row("SELECT * FROM `{$wpdb->prefix}importyml_offer` where `project_id` = '{$new_id}' and `id` = '{$offers[$i]->id}' ");
			if ($updatedOffer != null){
				//$permalink = get_permalink($offer->affiliate_id);
				$a1[] = $offers[$i]->id;
				if ($offers[$i]->offer_available != $updatedOffer->offer_available) {
					$updOn++;
					$project->setAvailableOffer($offers[$i]->id,1);
					//echo "Update available for <a href='{$permalink}'>{$permalink}</a> {$offer->offer_available}|{$updatedOffer->offer_available} <br/>";
				}
				if ($offers[$i]->offer_price != $updatedOffer->offer_price) {
					$updPrice++;
					$project->setCost($offers[$i]->id,$updatedOffer->offer_price);
					//echo "Update price for <a href='{$permalink}'>{$permalink}</a> {$offer->offer_price}|{$updatedOffer->offer_price}<br/>";
				}
				
			} else {
				// Offer не найден, удаляем
				//$permalink = get_permalink($offer->affiliate_id);			
				//echo "Turn off <a href='{$permalink}'>{$permalink}</a><br/>";
				$project->setUnAvailableOffer($offers[$i]->id,0);
				$updOff++;
			}
		
		}
 
		$count_u = sizeOf($updatedOffers);
		
		for($i = 0; $i < $count_u; ++$i){
			//$updatedOffer = $this->getOffer($offers,$offer->id);
			$updatedOffer =$wpdb->get_row("SELECT * FROM `{$wpdb->prefix}importyml_offer` where `project_id` = '{$old_id}' and `id` = '{$updatedOffers[$i]->id}' ");
			if ($updatedOffer == null)  {/*
				if (!in_array($updatedOffers[$i]->id,$a1)) { 				
					//$permalink = get_permalink($updatedOffer->affiliate_id);						
					if ($updatedOffers[$i]->offer_available != $updatedOffer->offer_available) {
						$updOn++;
						$project->setAvailableOffer($updatedOffers[$i]->id,1);
						//echo "Update available for <a href='{$permalink}'>{$permalink}</a> {$offer->offer_available}|{$updatedOffer->offer_available} <br/>";
					}
					if ($updatedOffers[$i]->offer_price != $updatedOffer->offer_price) {
						$updPrice++;
						$project->setCost($updatedOffers[$i]->id,$offer->offer_price);
						//echo "Update price for <a href='{$permalink}'>{$permalink}</a> {$offer->offer_price}|{$updatedOffer->offer_price}<br/>";
					}
				}
			} else  {		*/		
				//echo "{$updatedOffer->id}";	
				$limit = 5;
				$counter = 1;	
				if($counter <= $limit) {
				$postID = $project->addOffer($updatedOffers[$i]);
				$updNew++;
				$counter++;
				}
			}
		}
		$wpdb->insert("{$wpdb->prefix}importyml_changed",array(
			'project_id' => $old_id,
			'changed' =>  current_time('mysql', 1),
			'updated_price'=>$updPrice,
			'updated_on'=>$updOn,
			'updated_off'=>$updOff,
			'updated_new'=>$updNew
		));
		echo "<hr/>";
		echo "Обновлено цен: {$updPrice}</br>";
		echo "Включено: {$updOn}</br>";
		echo "Отключено: {$updOff}</br>";
		echo "Новых: {$updNew}</br>";
	}

}
