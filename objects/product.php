<?php
include_once ('../config/database.php');
class ProductObject {
    public $db;
    public $table_name;
    private $dataConvert = [];

    public function __construct($table_name) {
        $this->table_name = $table_name;

        $database = new Database();
        $this->db = $database->getConnect();
    }

    public function getProduct() {
        $database = $this->db;
        $query = "SELECT
                c.name as category_name, p.id, p.name, p.description, p.price, p.category_id, p.created
            FROM
                " . $this->table_name . " p
                LEFT JOIN
                    categories c
                        ON p.category_id = c.id
            ORDER BY
                p.created DESC";
        $statement = $database->prepare($query);
        $statement->execute();
        return $this->convertData($statement);
    }

    public function convertData($statement) {
        $numberRow = $statement->rowCount();
        if ($numberRow > 0) {
            while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $item = [
                    "id" => $row['id'],
                    "name" => $row['name'],
                    "description" => html_entity_decode($row['description']),
                    "price" => $row['price'],
                    "category_id" => $row['category_id'],
                    "category_name" => $row['category_name']
                ];

                array_push($this->dataConvert, $item);
            }
            http_response_code(200);

            return json_encode($this->dataConvert);
        } else {
         http_response_code(404);
         return json_encode(
             ["message" => "Not found product"]
         );
        }
    }
}