<?php
    include 'connect.php';
    include 'require-signin.php';

    if (isset($_GET["itemid"])){

        $sql = "SELECT i.[Item ID], i.[Checked Out By] as chkOutBy,
            -- Book data --
            b.ISBN as bISBN, b.Title as bTitle, b.[Year Published] as bYearPub, b.AuthorFName as bAuthFName, b.AuthorMName as bAuthMName, b.AuthorLName as bAuthLName,
            b.Genre as bGenre, b.DDN as bDDN,
    
            -- Media data --
            m.[Media ID] as mID, m.Title as mTitle, m.[Year Published] as mYearPub, m.Genre as mGenre, m.AuthorFName as mAuthFName, m.AuthorMName as mAuthMName, m.AuthorLName as mAuthLName,
    
            -- Device data --
            d.[Model No.] as dModelNo, d.Name as dName, d.Type as dType, d.Manufacturer as dManu
        FROM library.library.Item as i
        LEFT OUTER JOIN library.library.[Book Title] as b ON i.[Book Title ID] = b.ISBN
        LEFT OUTER JOIN library.library.[Device Title] as d ON i.[Device Title ID] = d.[Model No.]
        LEFT OUTER JOIN library.library.[Media Title] as m ON i.[Media Title ID] = m.[Media ID]
        WHERE i.[Item ID] = ?";

        $result = sqlsrv_query($conn, $sql, array($_GET["itemid"]));
        $data = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);

        if (!$data){
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode((object)[]); // Return empty object
            return;
        }

        $returnObj = null;

        if ($data['bISBN']){
            $resultObj = (object) [
                'Item Type' => 'Book',
                'Title' => $data['bTitle'],
                'Author' => join(", ", [ $data['bAuthLName'], $data['bAuthMName'], $data['bAuthLName'] ]),
                'Genre' => $data['bGenre'],
                'DDN' => $data['bDDN'],
                'ISBN' => $data['bISBN'],
                'Year Published' => $data['bYearPub'],
                'Is Checked Out' => $data['chkOutBy'] ? true : false
            ];
        }
        else if ($data['mID']){
            $resultObj = (object) [
                'Item Type' => 'Media',
                'Title' => $data['mTitle'],
                'Author' => join(", ", [ $data['mAuthLName'], $data['mAuthMName'], $data['mAuthLName'] ]),
                'Genre' => $data['mGenre'],
                'Year Published' => $data['bYearPub'],
                'ID' => $data['mID'],
                'Is Checked Out' => $data['chkOutBy'] ? true : false
            ];
        }
        else if ($data['dModelNo']){
            $resultObj = (object) [
                'Item Type' => 'Device',
                'Name' => $data['dName'],
                'Manufacturer' => $data['dManu'],
                'Type' => $data['dType'],
                'Model No.' => $data['dModelNo'],
                'Is Checked Out' => $data['chkOutBy'] ? true : false
            ];
        }
        else
        {
            $resultObj = (object) [
                'Item Type' => 'Unknown',
                'Is Checked Out' => $data['chkOutBy'] ? true : false
            ];
        }
        
        // Return a JSON response
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($resultObj);
    }
?>