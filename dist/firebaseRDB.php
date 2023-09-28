<?php
/*
 * class name: firebaseRDB
 * version: 1.0
 * author: Devisty
 */


class FirebaseRDB{
private $baseURL;

public function __construct($baseURL){
   $this->baseURL = rtrim($baseURL, '/');
}

private function sendRequest($path, $method, $data = null) {
   $url = $this->baseURL . $path;

   $ch = curl_init();
   curl_setopt($ch, CURLOPT_URL, $url);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

   if ($method === 'POST' || $method === 'PATCH' || $method === 'DELETE') {
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
      if ($data !== null) {
          curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
      }
  }

  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
  curl_setopt($ch, CURLOPT_TIMEOUT, 120);
  curl_setopt($ch, CURLOPT_HEADER, false);

  $response = curl_exec($ch);
  curl_close($ch);

  return $response;
}


   public function insert($table, $data){
      $path = "/$table.json";
        $response = $this->sendRequest($path, "POST", $data);
        return $response;
    }

    public function update($table, $uniqueID, $data) {
      $path = "/$table/$uniqueID.json";
      $response = $this->sendRequest($path, "PATCH", $data);
      return $response;
  }

  public function delete($table, $uniqueID) {
   $path = "/$table/$uniqueID.json";
   $response = $this->sendRequest($path, "DELETE");
   return $response;
}

public function retrieve($dbPath, $queryKey = null, $queryType = null, $queryVal = null) {
   $queryParams = [];

   if ($queryKey !== null && $queryType !== null && $queryVal !== null) {
       if ($queryType === "EQUAL") {
           $queryParams["orderBy"] = "\"$queryKey\"";
           $queryParams["equalTo"] = "\"$queryVal\"";
       } elseif ($queryType === "LIKE") {
           $queryParams["orderBy"] = "\"$queryKey\"";
           $queryParams["startAt"] = "\"$queryVal\"";
       }
   }
   if (!empty($queryParams)) {
      $query = '?' . http_build_query($queryParams);
  } else {
      $query = '';
  }

  $path = "/$dbPath.json$query";
  $response = $this->sendRequest($path, "GET");
  return $response;
}
}