<?php
include '../../db/db-connection.php';


function getMedications($userID, $conn) {
    $query = "
        SELECT 
            medication, 
            dosage, 
            unit, 
            timing 
        FROM 
            medication 
        WHERE 
            userID = $userID";
    
    $result = mysqli_query($conn, $query);
    
    if (!$result) {
        die("Error fetching medications: " . mysqli_error($conn));
    }

    $medications = [];
    while ($row = mysqli_fetch_assoc($result)) {
        
        $timingParts = explode(',', $row['timing']);
        
        foreach ($timingParts as $timing) {
            
            $timingDetail = explode(' ', trim($timing)); 
            $day = $timingDetail[0]; 
            $time = $timingDetail[1]; 

            
            $medications[] = [
                'medication' => $row['medication'],
                'dosage' => $row['dosage'],
                'unit' => $row['unit'],
                'day' => $day,   
                'time' => $time  
            ];
        }
    }

    return $medications;
}
