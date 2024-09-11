<?php
  function generate_random_token() {
    return bin2hex(openssl_random_pseudo_bytes(32));
  }
  class Session {
    private array $messages;

    public function __construct() {
      session_start();

      if (!isset($_SESSION['csrf'])) {
        $_SESSION['csrf'] = generate_random_token();
      }

      $this->messages = isset($_SESSION['messages']) ? $_SESSION['messages'] : array();
      unset($_SESSION['messages']);
    }

    public function getCSRF(){
      return isset($_SESSION['csrf']) ? $_SESSION['csrf'] : null;
    }
    public function isLoggedIn() : bool {
      return isset($_SESSION['id']);    
    }

    public function logout() {
      session_destroy();
    }

    public function getId() : ?int {
      return isset($_SESSION['id']) ? $_SESSION['id'] : null;    
    }

    public function getName() : ?string {
      return isset($_SESSION['name']) ? $_SESSION['name'] : null;
    }

    public function setId(int $id) {
      $_SESSION['id'] = $id;
    }

    public function setName(string $name) {
      $_SESSION['name'] = $name;
    }

    public function addMessage(string $type, string $text) {
      $_SESSION['messages'][] = array('type' => $type, 'text' => $text);
    }

    public function getMessages() {
      return $this->messages;
    }
      public function getCart(): array {
        return isset($_SESSION['shopping_cart']) ? $_SESSION['shopping_cart'] : [];
    }

    public function addToCart($item) {
      if (!isset($_SESSION['shopping_cart'])) {
          $_SESSION['shopping_cart'] = array(); 
      }
      $_SESSION['shopping_cart'][] = $item;
  }
  
    public function removeFromCart($index) {
        if (isset($_SESSION['shopping_cart'][$index])) {
            unset($_SESSION['shopping_cart'][$index]);
        }
    }
    public function removeFromCartById($itemId) {
      if(isset($_SESSION['shopping_cart'])) {
          foreach($_SESSION['shopping_cart'] as $index => $item) {
            if($item->id === $itemId) {
                 unset($_SESSION['shopping_cart'][$index]);
                  break; 
              }
          }
      }
    }

    public function clearCart() {
        $_SESSION['shopping_cart'] = array();
    }
  
  public function getNumberItemsInCart(){
    $num_items_in_cart = isset($_SESSION['shopping_cart']) ? count($_SESSION['shopping_cart']) : 0;
    return $num_items_in_cart;
  }
  public function findItemInCart($itemId) : bool{
    if(isset($_SESSION['shopping_cart'])) {
      foreach($_SESSION['shopping_cart'] as $index => $item) {
        if($item->id === $itemId) {
            return true;
        }
      }   
    }else{
      $_SESSION['shopping_cart'] = array(); 
      
    }
   return false;
  }
}
?>