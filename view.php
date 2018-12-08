<?php
include'./include/connect.php';
include'./include/auth_check.php';
?>
<!DOCTYPE html>
<html class="bootstrap-admin-vertical-centered">
    <head>
        <title>View page | Ventano</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!-- Bootstrap -->
        <link rel="stylesheet" media="screen" href="css/bootstrap.min.css">		
       
        <!-- Custom styles -->
        <link rel="stylesheet" media="screen" href="css/style.css">
        
    </head>
    <body>
        <div class="container">
            <div class="row">
			<div class="log-out"><a href="index.php?log=out">[ Sign Out ] </a></div>
			
			
                
						<div class="bootstrap-view-content">
                                <table class="table table-striped table-bordered" id="example">
                                    <thead>
                                        <tr>
                                            <th>Date of visit</th>
                                            <th>IP Address</th>
                                            <th>Visitor ID</th>
                                            <th>AD ID</th>
                                            <th>Total Page Visit</th>
											<th>Total Time Spent</th>
											<th>Visitor Name</th>
                                        </tr>
                                    </thead>
                                    
									<tbody>
									<?php									
									
									$limit = 2;
									$page = isset($_GET['nav']) ? $_GET['nav'] : 1;									
									$offset = $limit * ($page - 1);
									
									$q = "SELECT DATE(timestamp) visitDate FROM site_visitors GROUP BY DATE(timestamp) ORDER BY id DESC LIMIT $offset, $limit";
									$stmt = $pdo->prepare($q);
									$stmt->execute();									
									$data = $stmt->fetchAll();									
																											
									foreach ($data as $row)
									{			
										$visitDate = $row['visitDate'];
										$sql = "CALL getVisitorsInfo('".$visitDate."')";	// CALL STORE PROCEDURE				
										$stmt = $pdo->prepare($sql);
										$stmt->execute();
										$datas = $stmt->fetchAll();
										// var_export($datas);
										
										echo '<tr>
												<td colspan="7"><h4>'.$visitDate.'</h4></td>                                            
											</tr>';										
										
										foreach ($datas as $rows)
										{									
										?>
											
                                        <tr class="odd gradeA">
                                            <td><?php echo $rows['vdate']; ?></td>
                                            <td><?php echo $rows['visitors_ip']; ?></td>
                                            <td><?php echo $rows['visitors_id']; ?></td>
                                            <td><?php echo $rows['ad_id']; ?></td>
                                            <td><?php echo $rows['num_visitor']; ?></td>
											<td><?php echo gmdate("H:i:s", $rows['duration']); ?></td>
											<td>N/A</td>
                                        </tr>                                        
										<?php 
										}									
									}
									?>    
                                    </tbody>
                                </table>
                           
						<?php 
						/** PAGINATION **/
						
						$q = "SELECT count(1) AS total FROM site_visitors GROUP BY DATE(timestamp)";
						$stmt = $pdo->prepare($q);
						$stmt->execute();									
						$data = $stmt->fetchAll();
						
						$num = count($data);
						
						$nav_size=ceil(($num/$limit));
						
						if ($nav_size > 1) 
						{
							echo '<ul class="pagination">';							
								
								for ($i = 1; $i <= $nav_size; $i++) 
								{					
									$sel = ($page == $i) ? "class='active'" : "";
									echo '<li '.$sel.'><a href="view.php?nav='.$i.'">'.$i.'</a></li>';												
								} 				
							echo '<ul>';
						}
						?>
						
					</div>	
					
			</div>
        </div>

        
    </body>
</html>
