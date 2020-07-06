<?php
include_once ('../config/database.php');
include_once ('../error/validate.php');

class ProductObject {
    public $db;
    public $table_name;
    public $validate;
    private $dataConvert = [];

    public function __construct($table_name) {
        $this->table_name = $table_name;

        $database = new Database();
        $this->db = $database->getConnect();

        $validate = new Validate();
        $this->validate = $validate;
    }

    public function getProduct($id) {
        $database = $this->db;
        $query = "SELECT
                c.name as category_name, p.id, p.name, p.description, p.price, p.category_id, p.created
            FROM
                " . $this->table_name . " p
                LEFT JOIN
                    categories c
                        ON p.category_id = c.id ";
        if ($id) {
            $query .= "WHERE p.id = {$id} ";
        }
            $query .= "ORDER BY
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
            return $this->validate->error(404);
        }
    }

    public function createProduct($productData) {
        if ($this->checkData($productData)) {
            return $this->validate->error(400);
        }
        $database = $this->db;
        $query = "INSERT INTO
                " . $this->table_name . "
            SET
                name=:name, price=:price, description=:description, category_id=:category_id, created=:created";
        $statement = $database->prepare($query);

        $name = htmlspecialchars(strip_tags($productData->name));
        $price = htmlspecialchars(strip_tags($productData->price));
        $description = htmlspecialchars(strip_tags($productData->description));
        $category_id = htmlspecialchars(strip_tags($productData->category_id));
        $created = date('Y-m-d H:i:s');

        $statement->bindParam(":name", $name);
        $statement->bindParam(":price", $price);
        $statement->bindParam(":description", $description);
        $statement->bindParam(":category_id", $category_id);
        $statement->bindParam(":created", $created);

        if ($statement->execute()) {
            return $this->validate->success(201);
        } else {
            return $this->validate->error(503);
        }
    }

    /**
     * @param object $productData
     * @return bool
     */
    public function checkData(object $productData): bool {
        return (!$productData
            && empty($productData->name)
            && empty($productData->price)
            && empty($productData->description)
            && empty($productData->category_id));
    }


    public function updateProduct(object $productData){
        if (!$this->isProduct($productData->id)) {
            return $this->validate->error(404);
        }
        $database = $this->db;
        $query = "UPDATE
                " . $this->table_name . "
            SET
                name = :name,
                price = :price,
                description = :description,
                category_id = :category_id
            WHERE
                id = :id";
        $statement = $database->prepare($query);
        $name = htmlspecialchars(strip_tags($productData->name));
        $price = htmlspecialchars(strip_tags($productData->price));
        $description = htmlspecialchars(strip_tags($productData->description));
        $category_id = htmlspecialchars(strip_tags($productData->category_id));
        $id = htmlspecialchars(strip_tags($productData->id));

        $statement->bindParam(":name", $name);
        $statement->bindParam(":price", $price);
        $statement->bindParam(":description", $description);
        $statement->bindParam(":category_id", $category_id);
        $statement->bindParam(":id", $id);

        if ($statement->execute()) {
            http_response_code(200);
           return json_encode([
               "message" => "Product was updated."
           ]);
        }

        return $this->validate->error(503);
    }

    /**
     * @param $id
     * @return bool
     *
     */
    public function isProduct($id):bool {
        $database = $this->db;
        $query = "SELECT * FROM {$this->table_name} WHERE id = {$id}";
        $statement = $database->prepare($query);
        $statement->execute();
        $number = $statement->rowCount();

        return $number > 0;
    }

}