function ImportYml($)
{
	this.editingForm = function(idProject)
	{
		$("#iySaveProject").click(function(){
			if($('input[name=projectName]').val()==''||$('input[name=yi-projectYML]').val()==''){
				alert('Для добавления проекта заполните необходимые поля');
			}else{
				$('[name="yi-import-template"]').val($('[name="importTemplate"]').val());
				$("[name='yi-projectForm']").submit();
			}
		});
		
		if (idProject == "new")
		{

		}
		else
		{
			//$("#iySaveProject").attr('disabled','disabled');
			
			$('#yiShowImportView').click(function()
			{
				$("#importView").css('display','block');
			});
			
			
			//После нажатия на кнопку происходить полный импорт категорий и записей
			$("#iyImportProject").click(function()
			{
				$.ajax({
					url: 'tools.php',
					data: {'iy-ajax':1,'iy-project-id':idProject,'iy-project-action':'createCategories'},
					async: true,
					dataType: "json",
					success: function(data)
					{

					}
				});
				
				// Добавить функция создания категорий
				var offers = null;
				$.ajax({
					url: 'tools.php',
					data: {'iy-ajax':1,'iy-project-id':idProject,'iy-project-action':'getOffers'},
					async: false,
					dataType: "json",
					success: function(data)
					{
						offers = data;
					}
				});

				$("#createPostBarMessage .total").html(offers.length);
				var howmany = 0;
				$( "#createPostBar" ).progressbar({value: 0});
				
				for (var i in offers)
				{
					$.ajax({
						url: 'tools.php',
						data: {'iy-ajax':1,'iy-project-id':idProject,'iy-project-action':'createPost','offer_id' : offers[i].id},
						async: true,
						//dataType: "json",
						success: function(data)
						{
							var one = 100 / offers.length;
							howmany++;
							$("#createPostBarMessage .howmany").html(howmany);
							jQuery( "#createPostBar" ).progressbar({
								value: jQuery( "#createPostBar" ).progressbar('value') + one
							});
							
							//$("#importView .console").append(data + "; ");
						}
					});
				}
				
				
				$.ajax({
					url: 'tools.php',
					data: {'iy-ajax':1,'iy-project-id':idProject,'iy-project-action':'recalculate'},
					async: true,
					dataType: "json",
					success: function(data)
					{

					}
				});
				
			});
			
			$("#yiAnalizeProject").click(function()
			{	
				$.ajax({
					  url: 'tools.php',
					  data: {'iy-ajax':1,'iy-project-id':idProject,'iy-project-action':'analize'},
					  success: function(data)
					  {
						  alert('Анализ и создание контента прошло успешно!');
					  }
					});
			});
			
			$("#yiCronProject").change(function()
			{	
				var duration= $(this).val();
				$.ajax({
					  url: 'tools.php',
					  data: {'iy-project-action':'crone','iy-time':duration},
					  success: function(data)
					  {
						 
					  }
					});
			});
			
			
			$("#yiDeleteProjectContent").click(function()
			{
				jQuery('<div id="parser_shadow_window"></div>').prependTo('body');
				jQuery('<div id="parser_background_message"><div><a id="continueButton">X</a><h2>Удаление медиафайлов</h2><div id="createPostBar_attach"></div><div id="createPostBarMessage_attach" style="font-size:20px;margin:10px 0;">Обработано <span class="howmany">0</span> из <span class="total">0</span></div><h2>Удаление записей</h2><div id="createPostBar_post"></div><div id="createPostBarMessage_post" style="font-size:20px;margin:10px 0;">Обработано <span class="howmany">0</span> из <span class="total">0</span></div></div></div>').prependTo('body');
				var attachs = null;
				$.ajax({
					  url: 'tools.php',
					  data: {'iy-ajax':1,'iy-project-id':idProject,'iy-project-action':'getAttachs'},
					  async: false,
					  dataType: "json",
					  success: function(data)
					  {
						  attachs = data;
					  }
				});		
				$("#createPostBarMessage_attach .total").html(attachs.length);
				var howmany = 0;
				$( "#createPostBar_attach" ).progressbar({value: 0});				
				for (var q in attachs)
				{
					$.ajax({
						url: 'tools.php',
						data: {'iy-ajax':1,'iy-project-id':idProject,'iy-project-action':'deletePosts','post_id' : attachs[q].id},
						async: true,
						success: function(data)
						{
							var one = 100 / attachs.length;
							howmany++;
							$("#createPostBarMessage_attach .howmany").html(howmany);
							jQuery( "#createPostBar_attach" ).progressbar({
								value: jQuery( "#createPostBar_attach" ).progressbar('value') + one
							});
						}
					});
				} 
				
				var posts = null;
				$.ajax({
					  url: 'tools.php',
					  data: {'iy-ajax':1,'iy-project-id':idProject,'iy-project-action':'getPosts'},
					  async: false,
					  dataType: "json",
					  success: function(data)
					  {
						  posts = data;
					  }
				});	
				
				$("#createPostBarMessage_post .total").html(posts.length);
				var howmany_post = 0;
				$( "#createPostBar_post" ).progressbar({value: 0});						
				
				for (var p in posts)
				{
					$.ajax({
						url: 'tools.php',
						data: {'iy-ajax':1,'iy-project-id':idProject,'iy-project-action':'deletePosts1','post_id' : posts[p].id},
						async: true,
						success: function(data)
						{
							var piece = 100 / posts.length;
							howmany_post++;
							$("#createPostBarMessage_post .howmany").html(howmany_post);
							jQuery( "#createPostBar_post" ).progressbar({
								value: jQuery( "#createPostBar_post" ).progressbar('value') + piece
							});
						}
					});
				} 
				
				$.ajax({
					  url: 'tools.php',
					  data: {'iy-ajax':1,'iy-project-id':idProject,'iy-project-action':'deleteContent'},
					  async: false,
					  success: function(data)
					  {
						
					  }
				});		
				
				jQuery('#continueButton').click(function() {
					jQuery('#parser_shadow_window').remove();
					jQuery('#parser_background_message').remove();
					return false;
				});
			});

		}
		
		
		
	}
	
	this.init = function()
	{
		if ($("[name='import-yml-project']").val() !="undefined")
		{
			/* Значит работаем с формой редактирования.*/
			this.editingForm($("[name='import-yml-project']").val());
		}
	}
	this.init();

	this.updateProject = function(id,img_path) {
		jQuery('<div id="parser_shadow_window"></div>').prependTo('body');
		jQuery('<div id="parser_background_message"><div><a id="continueButton">X</a><h2 class="wait">Ожидайте идет обновление!</h2><img class="wait_img" src="'+img_path+'/loader.gif"/><div id="iy-log1"></div></div></div>').prependTo('body');
		var url = "tools.php?iy-ajax&iy-project-id=" + id + "&iy-project-action=update";
		$.get(url,function(data) {
			
		}).done(function(data) {
			$('#iy-log1').html(data);
			$('.wait').hide();
			$('.wait_img').hide();
		})
		.fail(function(data) {
			$('#iy-log1').html('<h2>Ошибка выполнения скрипта</h2><br><p>Для обновления большого кол-ва товарных позиций требуются большие вычислительные ресурсы сервера. Попробуйте увеличить параметр "memory_limit" до 128M</p>');
			$('.wait').hide();
			$('.wait_img').hide();
		});
		
		jQuery('#continueButton').click(function() {
					jQuery('#parser_shadow_window').remove();
					jQuery('#parser_background_message').remove();
					return false;
		});
	}
}

jQuery(function($)
{
	window.iy = new ImportYml($);
});
