<?php
session_start();
$user=$_SESSION['username'];
date_default_timezone_set('America/New_York');/*AS it will default take europe timing we need to need to set time zone*/

if(isset($_POST['func']) && !empty($_POST['func'])){
	switch($_POST['func']){
		case 'getCalender':
			getCalender($_POST['year'],$_POST['month'],$_POST['day']);
			break;
		case 'getEvents':
			getEvents($_POST['date']);
			break;
		//For Add Event
		case 'addEvent':
			addEvent($_POST['date'],$_POST['title'],$_SESSION['username'],$_POST['etime']);
			break;
         case 'editEvent':
			editEvents($_POST['date'],$_POST['title'],$_POST['id']);
			break;
		case 'deleteevents':
			deleteevents($_POST['id']);
			break;
		default:
			break;
	}
}

/*
 * Get calendar full HTML
 */
function getCalender($year = '',$month = '',$day='')
{
	$dateYear = ($year != '')?$year:date("Y");
	$dateMonth = ($month != '')?$month:date("m");
    $Day=($day != '')?$day:date("d");
	$date = $dateYear.'-'.$dateMonth.'-01';
	$currentMonthFirstDay = date("N",strtotime($date));
	$totalDaysOfMonth = cal_days_in_month(CAL_GREGORIAN,$dateMonth,$dateYear);
	$totalDaysOfMonthDisplay = ($currentMonthFirstDay == 7)?($totalDaysOfMonth):($totalDaysOfMonth + $currentMonthFirstDay);
	$boxDisplay = ($totalDaysOfMonthDisplay <= 35)?35:42;
?>
	<div id="calendar">
		<h2>
            <a href="javascript:void(0);" onclick="getCalendar('calendar_div','<?php echo date("Y",strtotime($date.' - 1 Year')); ?>','<?php echo date("m",strtotime($date.' - 1 Year')); ?>');">&lt;&lt;</a>
        	<a href="javascript:void(0);" onclick="getCalendar('calendar_div','<?php echo date("Y",strtotime($date.' - 1 Month')); ?>','<?php echo date("m",strtotime($date.' - 1 Month')); ?>');">prev</a>
            <select name="month_dropdown" class="month_dropdown dropdown"><?php echo getAllMonths($dateMonth); ?></select>
            
            <select name="day_dropdown" class="day_dropdown dropdown"><?php echo getDays($Day); ?></select>
			<select name="year_dropdown" class="year_dropdown dropdown"><?php echo getYearList($dateYear); ?></select>
            <a href="javascript:void(0);" onclick="getCalendar('calendar_div','<?php echo date("Y",strtotime($date.' + 1 Month')); ?>','<?php echo date("m",strtotime($date.' + 1 Month')); ?>');">next</a>
            
            <a href="javascript:void(0);" onclick="getCalendar('calendar_div','<?php echo date("Y",strtotime($date.' + 1 Year')); ?>','<?php echo date("m",strtotime($date.' + 1 Year')); ?>');">&gt;&gt;</a>
        </h2>
        <div id="event_list" class="none"></div>
        <!--For Add Event-->
        <div id="event_add" class="none">
        	<p>Add Event on <span id="eventDateView"></span></p>
            <p><b>Event Title: </b><input type="text" id="eventTitle" value=""/></p>
            <p><b>Event Time: </b><input type="text" id="etime" value=""/></p>
            <input type="hidden" id="eventDate" value=""/>
            <P>Do you want the event to be changed to public? </P>  
            
  <input type="radio" name="eve" value="private" checked> private
  <input type="radio" name="eve" value="public"> public<br>
            <input type="button" id="addEventBtn" value="Add"/>
            
        </div>
        <div id="event_edit" class="none">
        	<p>Edit Event on <span id="editeventDateView"></span></p>
            <p><b>Event Title: </b><input type="text" id="editeventTitle" value=""/></p>
            
            <input type="hidden" id="editeventDate" value=""/>
            
            <input type="button" id="editaddEventBtn" value="Add" onclick="editButtonClick();"/>
            <input type="hidden" id="editeventId" value=""/>
        </div>
		<div id="calendar_top">
			<ul>
				<li>Sun</li>
				<li>Mon</li>
				<li>Tue</li>
				<li>Wed</li>
				<li>Thu</li>
				<li>Fri</li>
				<li>Sat</li>
			</ul>
		</div>
		<div id="calendar_bottom">
			<ul>
			<?php 
				$dayCount = 1; 
				for($cb=1;$cb<=$boxDisplay;$cb++){
					if(($cb >= $currentMonthFirstDay+1 || $currentMonthFirstDay == 7) && $cb <= ($totalDaysOfMonthDisplay)){
						//Current date
						$currentDate = $dateYear.'-'.$dateMonth.'-'.$dayCount;
                        $selectedDate = $dateYear.'-'.$dateMonth.'-'.$Day;
						$eventNum = 0;
                        $user = $_SESSION['username'];
						//Include db configuration file
						include 'dbConfig.php';
						//Get number of events based on the current date
						$result = $db->query("SELECT title,uloginname FROM events WHERE date = '".$currentDate."' AND uloginname = '".$user."' AND status = 1");
						$eventNum = $result->num_rows;
						//Define date cell color
                        if($currentMonthFirstDay != $Day){
                        if(strtotime($selectedDate) == strtotime($currentDate)){
                    echo '<li date="'.$currentDate.'" class="palegreen date_cell">';    
                }elseif($eventNum > 0){
							echo '<li date="'.$currentDate.'" class="light_sky date_cell">';
						}
                            else {
                    echo '<li date="'.$currentDate.'" class="date_cell">';
                }
            }
            else{
						if(strtotime($currentDate) == strtotime(date("Y-m-d"))){
							echo '<li date="'.$currentDate.'" class="palegreen date_cell">';
						}elseif($eventNum > 0){
							echo '<li date="'.$currentDate.'" class="light_sky date_cell">';
						}else{
							echo '<li date="'.$currentDate.'" class="date_cell">';
						}
            }
						//Date cell
						echo '<span>';
						echo $dayCount;
						echo '</span>';
						
						//Hover event popup
						echo '<div id="date_popup_'.$currentDate.'" class="date_popup_wrap none">';
						echo '<div class="date_window">';
						echo '<div class="popup_event">Events ('.$eventNum.')</div>';
						echo ($eventNum > 0)?'<a href="javascript:;" onclick="getEvents(\''.$currentDate.'\');">view events</a><br/>':'';
						//For Add Event
						echo '<a href="javascript:;" onclick="addEvent(\''.$currentDate.'\');">add event</a>';
						echo '</div></div>';
						
						echo '</li>';
						$dayCount++;
			?>
			<?php }else{ ?>
				<li><span>&nbsp;</span></li>
			<?php } } ?>
			</ul>
		</div>
	</div>

	<script type="text/javascript">
		$('#event_add').hide();
	$('#event_edit').hide();
        function getCalendar(target_div,year,month,day){
			$.ajax({
				type:'POST',
				url:'main.php',
				data:'func=getCalender&year='+year+'&month='+month+'&day='+day,
				success:function(html){
					$('#'+target_div).html(html);
				}
			});
		}
		
		function getEvents(date){
			
            $.ajax({
				type:'POST',
				url:'main.php',
				data:'func=getEvents&date='+date,
				success:function(html){
					$('#event_list').html(html);
					$('#event_add').slideUp('slow');
					$('#event_list').slideDown('slow');
				}
			});
		}
        
		function deleteevents(date,id){
			$.ajax({
				type:'POST',
				url:'main.php',
				data:'func=deleteevents&id='+id,
				success:function(msg){
					if(msg == 'ok'){
							var dateSplit = date.split("-");
							//$('#eventTitle').val('');
							alert('Event deleted Successfully.');
							getCalendar('calendar_div',dateSplit[0],dateSplit[1]);
						}else{
							alert('Some problem occurred, please try again.');
						}
				}
			});
		}
		//For Add Event
		function addEvent(date){
			$('#eventDate').val(date);
			$('#eventDateView').html(date)
			$('#event_list').slideUp('slow');
			$('#event_add').slideDown('slow');
		}
        
        
		//For Add Event
		$(document).ready(function(){
			$('#addEventBtn').on('click',function(){
				var date = $('#eventDate').val();
				var title = $('#eventTitle').val();
                var etime = $('#etime').val();
				$.ajax({
					type:'POST',
					url:'main.php',
					data:'func=addEvent&date='+date+'&title='+title+'&etime='+etime,
					success:function(msg){
						if(msg == 'ok'){
							var dateSplit = date.split("-");
							$('#eventTitle').val('');
							alert('Event Created Successfully.');
							getCalendar('calendar_div',dateSplit[0],dateSplit[1]);
						}else{
							alert('Some problem occurred, please try again.');
						}
					}
				});
			});
		});
        
        function editEvent(date,id,title){
            $('#event_add').hide();
            $('#event_list').hide();
            $('#editeventDateView').html(date);
            $('#editeventTitle').val(title);
            $('#editeventDate').val(date);
            $('#editeventId').val(id);
            $('#event_edit').show();
            
        }

function editButtonClick(){
            var date = $('#editeventDate').val();
            var title = $('#editeventTitle').val();
            var id=$('#editeventId').val();
          $.ajax({
					type:'POST',
					url:'main.php',
					data:'func=editEvent&date='+date+'&title='+title+"&id="+id,
					success:function(msg){
						if(msg.includes('ok')){
                            var dateSplit = date.split("-");
							$('#editeventTitle').val('');
                            $('#editeventId').val('');
							alert('Event Edited Successfully.');
							getCalendar('cal',dateSplit[0],dateSplit[1]);
                            $('#event_edit').hide();
                            
						}else{
							alert("msg"+msg);
						}
					}
				});
}
		
        
		$(document).ready(function(){
			$('.date_cell').mouseenter(function(){
				date = $(this).attr('date');
				$(".date_popup_wrap").fadeOut();
				$("#date_popup_"+date).fadeIn();	
			});
			$('.date_cell').mouseleave(function(){
				$(".date_popup_wrap").fadeOut();		
			});
			$('.month_dropdown').on('change',function(){
				getCalendar('calendar_div',$('.year_dropdown').val(),$('.month_dropdown').val(),$('.day_dropdown').val());
			});
            
            $('.day_dropdown').on('change',function(){
				getCalendar('calendar_div',$('.year_dropdown').val(),$('.month_dropdown').val(),$('.day_dropdown').val());
			});
			$('.year_dropdown').on('change',function(){
				getCalendar('calendar_div',$('.year_dropdown').val(),$('.month_dropdown').val(),$('.day_dropdown').val());
			});
			$(document).click(function(){
				$('#event_list').slideUp('slow');
			});
		});
	</script>
<?php
}

/*
 * Get months options list.
 */
function getAllMonths($selected = ''){
	$options = '';
	for($i=1;$i<=12;$i++)
	{
		$value = ($i < 01)?'0'.$i:$i;
		$selectedOpt = ($value == $selected)?'selected':'';
		$options .= '<option value="'.$value.'" '.$selectedOpt.' >'.date("F", mktime(0, 0, 0, $i+1, 0, 0)).'</option>';
	}
	return $options;
}

function getDays($selected = ''){
	$opt = '';
	for($i=1;$i<=31;$i++)
	{
        $selectedOpt = ($i == $selected)?'selected':'';
		$opt .= '<option value="'.$i.'" '.$selectedOpt.' >'.$i.'</option>';
	}
	return $opt;
    
}
/*
 * Get years options list.
 */
function getYearList($selected = ''){
	$options = '';
	for($i=2015;$i<=2025;$i++)
	{
		$selectedOpt = ($i == $selected)?'selected':'';
		$options .= '<option value="'.$i.'" '.$selectedOpt.' >'.$i.'</option>';
	}
	return $options;
}

/*
 * Get events by date
 */
function getEvents($date = ''){

	include 'dbConfig.php';
	$eventListHTML = '';
	$date = $date?$date:date("Y-m-d");

	$result = $db->query("SELECT title,id,time FROM events WHERE date = '".$date."' AND status = 1");
	if($result->num_rows > 0){
		$eventListHTML = '<h2>Events on '.date("l, d M Y",strtotime($date)).'</h2>';
		$eventListHTML .= '<ul>';
		while($row = $result->fetch_assoc()){ 
            $eventListHTML .= '<li>'.$row['title'].' - '.$row['time'].'&nbsp;&nbsp;&nbsp;<a href="javascript:void(0);" onclick="editEvent(\''.$date.'\','.$row['id'].',\''.$row['title'].'\');" style="color:red;">Edit</a>&nbsp;&nbsp;<a href="javascript:void(0);" onclick="deleteevents(\''.$date.'\','.$row['id'].');" style="color:red;">Delete</a></li>';
        }
        
		$eventListHTML .= '</ul>';
	}
	echo $eventListHTML;
}

/*
 * Add event to date
 */
function addEvent($date,$title,$user,$etime){
	
	include 'dbConfig.php';
	$currentDate = date("Y-m-d H:i:s");
	
	$insert = $db->query("INSERT INTO events (title,date,created,modified,uloginname,time) VALUES ('".$title."','".$date."','".$currentDate."','".$currentDate."','".$user."','".$etime."')");
	if($insert){
		echo 'ok';
	}else{
		echo 'err';
	}
}
function deleteevents($id){

	include 'dbConfig.php';

	$currentDate = date("Y-m-d H:i:s");

	

	$delete = $db->query("DELETE FROM events WHERE id='".$id."'");

	if($delete){

		echo 'ok';

	}else{

		echo 'err';

	}

}
function editEvents($date,$title,$id){
	include 'dbConfig.php';
	$currentDate = date("Y-m-d H:i:s");
    $id=(int) $id;
	$update = $db->query("UPDATE events SET title='".$title. "' WHERE id=".$id.";");
	if($update){
		echo 'ok';
	}else{
		echo 'Not Updated';
	}
}


?>