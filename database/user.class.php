<?php
declare(strict_types = 1);

class User {
    public int $id;
    public string $username;
    public string $email;
    public ?string $type;
    public ?int $items_listed;
    public ?string $paymentInfo;
    public ?string $paymentMethod;
    

    public function __construct(int $id, string $username, string $email, string $type, ?int $items_listed = 0, ?string $paymentInfo = null, ?string $paymentMethod = null) {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->type = $type;
        $this->items_listed = $items_listed;
        $this->paymentInfo = $paymentInfo;
        $this->paymentMethod = $paymentMethod;
        
    }

    static public function createAndInsert(PDO $db, string $username, string $email, string $password, string $type, ?string $paymentInfo = null, ?string $paymentMethod = null): User {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT, ['cost' => 8]);
        $stmt = $db->prepare('INSERT INTO User (UserName, Email,UserPassword, UserType, PaymentInfo, PaymentMethod) VALUES (:username, :email, :password, :type, :paymentInfo, :paymentMethod)');
        $stmt->execute([
            ':username' => $username,
            ':email' => $email,
            ':password' => $hashedPassword,
            ':type' => $type,
            ':paymentInfo' => $paymentInfo,
            ':paymentMethod' => $paymentMethod
        ]);
        return new User((int)$db->lastInsertId(), $username, $email, $type, 0, $paymentInfo, $paymentMethod);
    }


    static public function getAllUsersFromDatabase(PDO $db): array {
        $stmt = $db->prepare('SELECT * FROM User');
        $stmt->execute();
        $users = [];
        while ($user = $stmt->fetch()) {
            $users[] = new User(
                (int)$user['UserId'],
                $user['UserName'],
                $user['Email'],
                $user['UserType'],
                (int)$user['ItemsListed'],
                $user['PaymentInfo'],
                $user['PaymentMethod']
            );
        }
        return $users;
    }

    static public function getUserWithPassword(PDO $db, string $emailOrUsername, string $password): ?User {
        $stmt = $db->prepare('
          SELECT UserId, UserName, Email, UserType, ItemsListed, UserPassword, PaymentInfo, PaymentMethod
          FROM User
          WHERE Email = :emailOrUsername OR UserName = :emailOrUsername
        ');
        $stmt->execute([':emailOrUsername' => $emailOrUsername]);
        
        if ($user = $stmt->fetch()) {
            if (password_verify($password, $user['UserPassword'])) {
                return new User(
                    (int)$user['UserId'],
                    $user['UserName'],
                    $user['Email'],
                    $user['UserType'],
                    (int)$user['ItemsListed'],
                    $user['PaymentInfo'],
                    $user['PaymentMethod']
                );
            }
        }
        return null;
    }

    static public function getUserIdByUsername(PDO $db, string $username): int {
        $stmt = $db->prepare('SELECT UserId FROM User WHERE UserName = :username');
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch();
        return (int)$user['UserId'];
    }

    static public function getUsernameById(PDO $db, int $id): string {
        $stmt = $db->prepare('SELECT UserName FROM User WHERE UserId = :id');
        $stmt->execute([':id' => $id]);
        $user = $stmt->fetch();
        return $user['UserName'];
    }

    static public function getUserByUsername(PDO $db, string $username): ?User {
        $stmt = $db->prepare('SELECT * FROM User WHERE UserName = :username');
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch();
        if ($user === false) {
            return null;
        }
        return new User((int)$user['UserId'], $user['UserName'], $user['Email'], $user['UserType'], (int)$user['ItemsListed'], $user['PaymentInfo'], $user['PaymentMethod']);
    }

    static public function getUserByEmail(PDO $db, string $email): ?User {
        $stmt = $db->prepare('SELECT * FROM User WHERE Email = :email');
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch();
        if ($user === false) {
            return null;
        }
        return new User((int)$user['UserId'], $user['UserName'], $user['Email'], $user['UserType'], (int)$user['ItemsListed'], $user['PaymentInfo'], $user['PaymentMethod']);
    }

    static public function getEmailByUsername(PDO $db, string $username): string {
        $stmt = $db->prepare('SELECT Email FROM User WHERE UserName = :username');
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch();
        return $user['Email'];
    }

    static public function getUserById(PDO $db, int $id): User {
        $stmt = $db->prepare('SELECT * FROM User WHERE UserId = :id');
        $stmt->execute([':id' => $id]);
        $user = $stmt->fetch();
        return new User((int)$user['UserId'], $user['UserName'], $user['Email'], $user['UserType'], (int)$user['ItemsListed'], $user['PaymentInfo'], $user['PaymentMethod']);
    }

    static public function getUserType(PDO $db, int $id): string {
        $stmt = $db->prepare('SELECT UserType FROM User WHERE UserId = :id');
        $stmt->execute([':id' => $id]);
        $user = $stmt->fetch();
        return $user['UserType'];
    }

    static public function getUserTypeByUsername(PDO $db, string $username): string {
        $stmt = $db->prepare('SELECT UserType FROM User WHERE UserName = :username');
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch();
        return $user['UserType'];
    }

    static public function getUserItemsListed(PDO $db, int $id): int {
        $stmt = $db->prepare('SELECT ItemsListed FROM User WHERE UserId = :id');
        $stmt->execute([':id' => $id]);
        $user = $stmt->fetch();
        return (int)$user['ItemsListed'];
    }


    static public function changePassword(PDO $db, int $id, string $password): void {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT, ['cost' => 8]);
        $stmt = $db->prepare('UPDATE User SET UserPassword = :password WHERE UserId = :id');
        $stmt->execute([
            ':password' => $hashedPassword,
            ':id' => $id
        ]);
    }

    static public function changePasswordName(PDO $db, string $username, string $password): void {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT, ['cost' => 8]);
        $stmt = $db->prepare('UPDATE User SET UserPassword = :password WHERE UserName = :username');
        $stmt->execute([
            ':password' => $hashedPassword,
            ':username' => $username
        ]);
    }

    static public function changeEmail(PDO $db, int $id, string $email): void {
        $stmt = $db->prepare('UPDATE User SET Email = :email WHERE UserId = :id');
        $stmt->execute([
            ':email' => $email,
            ':id' => $id
        ]);
    }

    static public function changeEmailName(PDO $db, string $username, string $email): void {
        $stmt = $db->prepare('UPDATE User SET Email = :email WHERE UserName = :username');
        $stmt->execute([
            ':email' => $email,
            ':username' => $username
        ]);
    }

    static public function changeUsername(PDO $db, int $id, string $username): void {
        $stmt = $db->prepare('UPDATE User SET UserName = :username WHERE UserId = :id');
        $stmt->execute([
            ':username' => $username,
            ':id' => $id
        ]);
    }

    static public function changerUsernameName(PDO $db, string $old_username, string $new_username): void {
        $stmt = $db->prepare('UPDATE User SET UserName = :new_username WHERE UserName = :old_username');
        $stmt->execute([
            ':new_username' => $new_username,
            ':old_username' => $old_username
        ]);
    }

    static public function changeType(PDO $db, int $id, string $type): void {
        $stmt = $db->prepare('UPDATE User SET UserType = :type WHERE UserId = :id');
        $stmt->execute([
            ':type' => $type,
            ':id' => $id
        ]);
    }

    static public function isUserAdmin(PDO $db, int $id): bool {
        $stmt = $db->prepare('SELECT UserType FROM User WHERE UserId = :id');
        $stmt->execute([':id' => $id]);
        $user = $stmt->fetch();
        return $user['UserType'] === 'admin';
    }

    static public function isUserSeller(PDO $db, int $id): bool {
        $stmt = $db->prepare('SELECT UserType FROM User WHERE UserId = :id');
        $stmt->execute([':id' => $id]);
        $user = $stmt->fetch();
        return $user['UserType'] === 'seller' || $user['UserType'] === 'admin';
    }

    static public function isUserBuyer(PDO $db, int $id): bool {
        $stmt = $db->prepare('SELECT UserType FROM User WHERE UserId = :id');
        $stmt->execute([':id' => $id]);
        $user = $stmt->fetch();
        return $user['UserType'] === 'buyer' || $user['UserType'] === 'admin';
    }

    static public function highestItemsListed(PDO $db): User {
        $stmt = $db->prepare('SELECT * FROM User ORDER BY items_listed DESC LIMIT 1');
        $stmt->execute();
        $user = $stmt->fetch();
        
        return new User((int)$user['UserId'], $user['UserName'], $user['Email'], $user['UserType'], (int)$user['ItemsListed'], $user['PaymentInfo'], $user['PaymentMethod']);
    }

    static public function emailExists(PDO $db, string $email): bool {
        $stmt = $db->prepare('SELECT * FROM User WHERE Email = :email');
        $stmt->execute([':email' => $email]);
        return $stmt->fetch() !== false;
    }

    static public function userNameExists(PDO $db, string $username): bool {
        $stmt = $db->prepare('SELECT * FROM User WHERE UserName = :username');
        $stmt->execute([':username' => $username]);
        return $stmt->fetch() !== false;
    }
    
    static public function updateUser(PDO $db, int $id, string $username, string $email, string $type): bool {
        $stmt = $db->prepare('UPDATE User SET UserName = :username, Email = :email, UserType = :type WHERE UserId = :id');
        return $stmt->execute([
            ':username' => $username,
            ':email' => $email,
            ':type' => $type,
            ':id' => $id
        ]);
    }

    static public function getIdfromUsername($db, $username) {
        $stmt = $db->prepare('SELECT UserId FROM User WHERE UserName = :username');
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch();
        return $user['UserId'];
    }

    static public function deleteUser(PDO $db, int $id): bool {
        $stmt = $db->prepare('DELETE FROM User WHERE UserId = :id');
        return $stmt->execute([':id' => $id]);
    }

    public function setPaymentMethod(?string $paymentMethod): void {
        $this->paymentMethod = $paymentMethod;
    }

    public function setShippingInfo(?string $shippingInfo): void {
        $this->shippingInfo = $shippingInfo;
    }

    public function setPaymentInfo(?string $paymentInfo): void {
        $this->paymentInfo = $paymentInfo;
    }

    public function hasPaymentInfo(): bool {
        return !is_null($this->paymentInfo);
    }

    public function hasShippingInfo(): bool {
        return !is_null($this->shippingInfo);
    }
}   
?>