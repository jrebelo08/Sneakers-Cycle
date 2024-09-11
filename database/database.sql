DROP TABLE IF EXISTS Item;
DROP TABLE IF EXISTS User;
DROP TABLE IF EXISTS Orders;
DROP TABLE IF EXISTS OrderItem;
DROP TABLE IF EXISTS Shipment;
DROP TABLE IF EXISTS ShipmentUserInfo;
DROP TABLE IF EXISTS Chat;
DROP TABLE IF EXISTS Message;

PRAGMA foreign_keys = ON;

CREATE TABLE User (
    UserId INTEGER PRIMARY KEY AUTOINCREMENT,
    UserName NVARCHAR(120) UNIQUE NOT NULL,
    Email NVARCHAR(120) NOT NULL,
    UserType NVARCHAR(5) NOT NULL, /* buyer buyer/seller admin */
    UserPassword NVARCHAR(255) NOT NULL,
    ItemsListed INTEGER DEFAULT 0,
    ItemsSold INTEGER DEFAULT 0,
    PaymentMethod NVARCHAR(50) ,
    PaymentInfo NVARCHAR(255)
);

CREATE TABLE Item (
    ItemId INTEGER PRIMARY KEY AUTOINCREMENT,
    ItemBrand NVARCHAR(120) NOT NULL,
    ItemName NVARCHAR(120) NOT NULL,
    ItemPrice DECIMAL(10,2) NOT NULL,
    ItemOwner NVARCHAR(120) NOT NULL,
    ItemImage NVARCHAR(255), 
    ItemDescription NVARCHAR(255),
    ItemCategory NVARCHAR(50) NOT NULL,
    ItemCondition NVARCHAR(20) NOT NULL, 
    ItemSize NVARCHAR(20) NOT NULL,  
    FOREIGN KEY(ItemOwner) REFERENCES User(UserId) 
);

CREATE TABLE Orders (
    OrdersId INTEGER PRIMARY KEY AUTOINCREMENT,
    UserId INTEGER NOT NULL,
    OrderDate DATE NOT NULL,
    Total DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (UserId) REFERENCES User(UserId)
);

CREATE TABLE OrderItem (
    OrderItemId INTEGER PRIMARY KEY AUTOINCREMENT,
    OrdersId INTEGER NOT NULL,
    ItemId INTEGER NOT NULL,
    Quantity INTEGER NOT NULL,
    FOREIGN KEY (OrdersId) REFERENCES Orders(OrdersId),
    FOREIGN KEY (ItemId) REFERENCES Item(ItemId)
);

CREATE TABLE ShipmentUserInfo (
    ShipmentUserInfoId INTEGER PRIMARY KEY AUTOINCREMENT,
    UserId INTEGER NOT NULL,
    ShippingAddress NVARCHAR(200) NOT NULL,
    ShippingCity NVARCHAR(50) NOT NULL,
    ShippingZipCode NVARCHAR(10) NOT NULL,
    ShippingCountry NVARCHAR(50) NOT NULL,
    FOREIGN KEY (UserId) REFERENCES User(UserId)
);

CREATE TABLE Shipment (
    ShipmentId INTEGER PRIMARY KEY AUTOINCREMENT,
    OrdersId INTEGER NOT NULL,
    ShipmentDate DATE NOT NULL,
    ShipmentStatus NVARCHAR(50) NOT NULL,
    FOREIGN KEY (OrdersId) REFERENCES Orders(OrdersId)
);

CREATE TABLE Chat (
    ChatId INTEGER PRIMARY KEY AUTOINCREMENT,
    ItemId INTEGER NOT NULL,
    SenderId INTEGER NOT NULL,
    ReceiverId INTEGER NOT NULL,
    LastSuggestedPrice DECIMAL(10, 2),
    FOREIGN KEY (SenderId) REFERENCES User(UserId),
    FOREIGN KEY (ReceiverId) REFERENCES User(UserId)
);

CREATE TABLE Message (
    MessageId INTEGER PRIMARY KEY AUTOINCREMENT,
    ChatId INTEGER NOT NULL,
    SenderId INTEGER NOT NULL,
    ReceiverId INTEGER NOT NULL,
    Content TEXT NOT NULL,
    Timestamp DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ChatId) REFERENCES Chat(ChatId),
    FOREIGN KEY (SenderId) REFERENCES User(UserId),
    FOREIGN KEY (ReceiverId) REFERENCES User(UserId)
);

INSERT INTO User (UserName, Email, UserType, UserPassword, ItemsListed, PaymentMethod, PaymentInfo)
VALUES
('JohnDoe', 'johndoe@example.com', 'buyer/seller', '$2y$08$srXBNaOn/wVHuPPgSUM3U.I2e8b8DNIpltA8jUzAbKCbLVHk1DO2e', 1, 'Credit Card', '4111111111111111'),
('JaneSmith', 'janesmith@example.com', 'buyer/seller', '$2y$08$srXBNaOn/wVHuPPgSUM3U.I2e8b8DNIpltA8jUzAbKCbLVHk1DO2e', 3,'PayPal', 'janesmith@paypal.com'),
('MikeJohnson', 'mikejohnson@example.com', 'buyer/seller', '$2y$08$srXBNaOn/wVHuPPgSUM3U.I2e8b8DNIpltA8jUzAbKCbLVHk1DO2e', 2,'Credit Card', '4222222222222222'),
('EmilyDavis', 'emilydavis@example.com', 'buyer/seller', '$2y$08$srXBNaOn/wVHuPPgSUM3U.I2e8b8DNIpltA8jUzAbKCbLVHk1DO2e', 1,NULL, NULL),
('ChrisBrown', 'chrisbrown@example.com', 'buyer', '$2y$08$srXBNaOn/wVHuPPgSUM3U.I2e8b8DNIpltA8jUzAbKCbLVHk1DO2e', 0,'Debit Card', '4333333333333333'),
('AmandaWilson', 'amandawilson@example.com', 'buyer/seller', '$2y$08$srXBNaOn/wVHuPPgSUM3U.I2e8b8DNIpltA8jUzAbKCbLVHk1DO2e', 3,'PayPal', 'amandawilson@paypal.com'),
('DavidMartinez', 'davidmartinez@example.com', 'buyer/seller', '$2y$08$srXBNaOn/wVHuPPgSUM3U.I2e8b8DNIpltA8jUzAbKCbLVHk1DO2e', 2,'Credit Card', '4444444444444444'),
('SarahLee', 'sarahlee@example.com', 'buyer/seller', '$2y$08$srXBNaOn/wVHuPPgSUM3U.I2e8b8DNIpltA8jUzAbKCbLVHk1DO2e', 1,'Credit Card', '4555555555555555'),
('PaulWalker', 'paulwalker@example.com', 'buyer', '$2y$08$srXBNaOn/wVHuPPgSUM3U.I2e8b8DNIpltA8jUzAbKCbLVHk1DO2e', 0,NULL, NULL),
('LauraMoore', 'lauramoore@example.com', 'buyer', '$2y$08$srXBNaOn/wVHuPPgSUM3U.I2e8b8DNIpltA8jUzAbKCbLVHk1DO2e', 0,'Debit Card', '4666666666666666'),
('Rebelo', 'rebelo@example.com', 'admin', '$2y$08$srXBNaOn/wVHuPPgSUM3U.I2e8b8DNIpltA8jUzAbKCbLVHk1DO2e', 0,NULL, NULL),
('Carlos', 'carlos@example.com', 'admin', '$2y$08$srXBNaOn/wVHuPPgSUM3U.I2e8b8DNIpltA8jUzAbKCbLVHk1DO2e', 0,NULL, NULL),
('Tiago' , 'tiago@example.com', 'admin', '$2y$08$srXBNaOn/wVHuPPgSUM3U.I2e8b8DNIpltA8jUzAbKCbLVHk1DO2e', 0,NULL, NULL);


INSERT INTO Item (ItemBrand, ItemName, ItemPrice, ItemOwner, ItemImage, ItemDescription, ItemCategory, ItemCondition, ItemSize)
VALUES
('Wilson', 'Wilson Pro Staff Court tennis shoes White', 70, 2, '../uploads/sneakersWilson.png', 'Sapatilhas usadas para ténis', 'Male', 'Good', '42'),
('Sanjo', 'Sapatilhas em Couro', 55, 6, '../uploads/sneakersLeather.jpeg', 'Sapatilhas em Couro bastante confortáveis', 'Male', 'Very good', '44'),
('Nike', 'Nike Dunk', 80, 7, '../uploads/sneakersNikeDunk.png', 'Casuais', 'Male', 'Good', '45'),
('Christian Louboutin', 'Louboutin Seavast 2', 850, 1, '../uploads/sneakersLouboutin.jpg', 'Authentic.', 'Male', 'New without tags', '42'),
('Sanjo', 'Sanjo Amarelas', 62, 4, '../uploads/sneakersYellowSanjo.jpg', 'Nunca usadas', 'Female', 'Very good', '40'),
('Sanjo', 'Navy Sanjo', 45, 3, '../uploads/sneakersNavySanjo.jpg', 'Nunca usadas', 'Kids', 'New without tags', '36'),
('Adidas', 'Adidas Samba', 62, 8, '../uploads/sneakersSamba.png', 'Nunca usadas', 'Female', 'New without tags', '36'),
('Ralph Lauren', 'Sapatilha Ralph Lauren', 120, 3, '../uploads/sneakersRalphLauren.jpg', 'Novas com Etiqueta.', 'Male', 'New with tags', '45'),
('Asics', 'Asics Japan', 80, 6, '../uploads/sneakersAsics.jpg', 'Novas, com caixa.', 'Female', 'New with tags', '37'),
('Adidas', 'Adidas Gazelle Pink', 50, 2, '../uploads/sneakersGazelle.jpg', 'Usadas, mas em bom estado.', 'Female', 'Good', '36'),
('Adidas', 'Adidas Gazelle Black', 80, 2, '../uploads/sneakersGazelle2.jpg', 'Novas, bastante confortáveis', 'Female', 'New without tags', '36'),
('Adidas', 'Adidas Azuis', 25, 6, '../uploads/sneakersBlueAdidas.jpg', 'Muito uso', 'Kids', 'Bad', '36'),
('Puma', 'Puma Classic Sneakers Red', 90, 2, '../uploads/puma1.jpg', 'Classic red Puma sneakers.', 'Male', 'New with tags', '43'),
('Puma', 'Puma Suede Sneakers Black', 85, 3, '../uploads/puma2.jpg', 'Stylish black Puma suede sneakers.', 'Female', 'Satisfactory', '39'),
('Nike', 'Dunk Low', 100, 7, '../uploads/sneakersDunkLow.jpg', 'Sapatilhas confortáveis', 'Male', 'Very good', '41'),
('Puma', 'Puma Lifestyle Sneakers Gray', 80, 7, '../uploads/puma4.jpg', 'Casual gray Puma lifestyle sneakers.', 'Female', 'Bad', '38'),
('Hermes', 'Hermes High-Top Sneakers White', 950, 8, '../uploads/hermes1.jpg', 'Luxurious white Hermes high-top sneakers.', 'Male', 'New with tags', '42'),
('Hermes', 'Hermes Low-Top Sneakers Black', 900, 9, '../uploads/hermes2.jpg', 'Elegant black Hermes low-top sneakers.', 'Female', 'Very good', '37'),
('Quechua', 'Quechua Waterproof Hiking Boots', 120, 10, '../uploads/quechua1.jpg', 'Durable waterproof hiking boots from Quechua.', 'Male', 'Bad', '41'),
('Quechua', 'Quechua Outdoor Sandals', 45, 11, '../uploads/quechua2.jpg', 'Comfortable outdoor sandals for adventures.', 'Female', 'Satisfactory', '40'),
('Quechua', 'Quechua Backpack', 30, 12, '../uploads/quechua3.jpg', 'Spacious backpack for hiking and travel.', 'Male', 'Very good', '42'),
('Merrell', 'Merrell Trail Running Shoes', 110, 1, '../uploads/merrel2.jpg', 'Sturdy and supportive trail running shoes from Merrell.', 'Kids', 'Good', '45'),
('Merrell', 'Merrell Hiking Boots', 140, 5, '../uploads/merrel1.jpg', 'Reliable and comfortable hiking boots from Merrell.', 'Male', 'New with tags', '44');

INSERT INTO Chat (ItemId, SenderId, ReceiverId, LastSuggestedPrice)
VALUES
(1, 6, 2, 65.00),   -- JaneSmith (seller) and AmandaWilson (buyer) discussing Wilson Pro Staff
(2, 7, 6, 50.00),   -- AmandaWilson (seller) and DavidMartinez (buyer) discussing Sapatilhas em Couro
(3, 8, 7, 75.00),   -- DavidMartinez (seller) and SarahLee (buyer) discussing Nike Dunk
(4, 2, 1, 820.00),  -- JohnDoe (seller) and JaneSmith (buyer) discussing Louboutin Seavast 2
(5, 6, 4, 58.00),   -- EmilyDavis (seller) and AmandaWilson (buyer) discussing Sanjo Amarelas
(6, 1, 3, 40.00),   -- MikeJohnson (seller) and JohnDoe (buyer) discussing Navy Sanjo
(7, 3, 8, 60.00),   -- SarahLee (seller) and MikeJohnson (buyer) discussing Adidas Samba
(8, 2, 3, 110.00);  -- MikeJohnson (seller) and JaneSmith (buyer) discussing Sapatilha Ralph Lauren

INSERT INTO Message (ChatId, SenderId, ReceiverId, Content)
VALUES
(1, 6, 2, 'Hi, is the Wilson Pro Staff still available?'),
(1, 2, 6, 'Yes, it is available. Would you like to make an offer?'),
(1, 2, 6, 'Last Suggedted Price $65'),
(1, 6, 2, 'That sounds good. Let’s proceed with the transaction.'),

(2, 7, 6, 'Hello, I am interested in the Sapatilhas em Couro.'),
(2, 6, 7, 'Great choice! They are very comfortable.'),
(2, 6, 7, 'Last Suggedted Price $50?'),
(2, 7, 6, 'Sure, $50 works for me.'),

(3, 7, 8, 'Hi, are the Nike Dunk shoes still up for sale?'),
(3, 8, 7, 'Yes, they are.'),
(3, 8, 7, 'Last Suggedted Price $75'),
(3, 7, 8, 'Yes, $75 is acceptable.'),

(4, 2, 1, 'I am interested in the Louboutin Seavast 2.'),
(4, 1, 2, 'They are authentic and in great condition.'),
(4, 1, 2, 'Last Suggedted Price $820?'),
(4, 2, 1, 'That works for me. Let’s finalize it.'),

(5, 6, 4, 'Are the Sanjo Amarelas still available?'),
(5, 4, 6, 'Yes, they are new and never used.'),
(5, 4, 6, 'Last Suggedted Price $58?'),
(5, 6, 4, 'Yes, that sounds fair.'),

(6, 1, 3, 'Hi, I am interested in the Navy Sanjo shoes.'),
(6, 3, 1, 'They are still available.'),
(6, 3, 1, 'Last Suggedted Price $40?'),
(6, 1, 3, 'Deal! Let’s proceed.'),

(7, 3, 8, 'Is the Adidas Samba still available?'),
(7, 8, 3, 'Yes, they are brand new.'),
(7, 8, 3, 'Last Suggedted Price $60?'),
(7, 3, 8, 'Sure, $60 is fine.'),

(8, 2, 3, 'Hi, I am interested in the Ralph Lauren sneakers.'),
(8, 3, 2, 'They are new with tags.'),
(8, 3, 2, 'Last Suggedted Price $110?'),
(8, 2, 3, 'Yes, $110 works for me.');
