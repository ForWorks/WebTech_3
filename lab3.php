<!DOCTYPE html>
<head>
    <meta charset="UTF-8" lang="ru">
	<meta name="description" content="This is second lab">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
	<link rel="stylesheet" href="lab3.css" type="text/css">
    <title>Test</title>    
</head>
<body>	
	<main>				
		<h1></h1>
		<form method="POST"> <!--Используем метод Post-->		
			<textarea name='way' placeholder="Way"><?php if (isset($_POST['enter'])) { echo $_POST['way'];}?></textarea><br>
			<input type="date" name='date1' value="<?php if (isset($_POST['enter'])) { echo $_POST['date1'];}?>"><br>
			<input type="date" name='date2' value="<?php if (isset($_POST['enter'])) { echo $_POST['date2'];}?>"><br>			
			<input id="button" type="submit" name="enter" value="Найти"><br><br>
			<?php				
				error_reporting(0); // error_reporting(E_ALL && ~E_WARNING);
				if (isset($_POST['enter'])) {
					// кладем в переменные сообщения взятые из формы
					$way = $_POST['way'];
					$date1 = $_POST['date1'];
					$date2 = $_POST['date2'];
				}							
				$flag = false;     // проверка нашлись ли файлы
				$date_1 = strtotime($date1);	// получаем дату от
				$date_2 = strtotime($date2);		// получаем дату до	
						
				function recursive($dir) {
					static $deep = 1; 
					global $date_1;
					global $date_2;
					global $flag;
					$dirr = opendir($dir);	//открываем директорию
					while (($file = readdir($dirr)) !== false){ // читаем ее пока не конец
						if ($file == '.' || $file == '..') {
							continue;  // чтобы не зацикливалось из-за салок на самих себя 
						}
						else {							
							$date = filectime($dir.DIRECTORY_SEPARATOR.$file);	// получаем дату 								
							if($date_1 <= $date && $date_2 >= $date) {   // если попали в промежуток - выводим
								echo $deep.' '.$dir.DIRECTORY_SEPARATOR.$file.'<br>';								
								$flag = true;	// что-то нашли	
							}
						} 
						if (is_dir($dir.DIRECTORY_SEPARATOR.$file)) { // если нашли папку - заходим в нее
							$deep++;
							recursive($dir.DIRECTORY_SEPARATOR.$file);
							$deep--;  // возврат из папки
						}
					}					
					closedir($dirr);	// закрываем директорию
				}							

				if(is_dir($way) && $date_2 > $date_1 && $date1 != '') {		// все корректно			
					recursive($way);						
				}		
				else if(!is_dir($way) || $date_2 <= $date_1 || $date1 == '') {
					if(!is_dir($way)) {  // если неправльный путь
						echo 'Проверьте корректность указанного пути.<br>';
					}
					if($date_2 <= $date_1 || $date1 == '') {  // если неправильные даты
						echo 'Проверьте корректность указанных дат.';
					}						
					$flag = true;
				}
				if(!$flag) {					// если ничего не найдено
					echo 'Ничего не найдено!';
				}
			?>								
		</form>		
	</main>
</body>
</html>