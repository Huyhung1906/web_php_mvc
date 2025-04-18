-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 18, 2025 at 06:25 PM
-- Server version: 10.6.21-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shoes_web`
--

-- --------------------------------------------------------

--
-- Table structure for table `address`
--

CREATE TABLE `address` (
  `id_address` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `province` varchar(100) DEFAULT NULL,
  `district` varchar(100) DEFAULT NULL,
  `street` varchar(100) DEFAULT NULL,
  `ward` varchar(100) DEFAULT NULL,
  `address_type` varchar(100) DEFAULT NULL,
  `created_date` date DEFAULT NULL,
  `update_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `address`
--

INSERT INTO `address` (`id_address`, `id_user`, `province`, `district`, `street`, `ward`, `address_type`, `created_date`, `update_date`) VALUES
(1, 1, 'Hà Nội', 'Ba Đình', '123 Đội Cấn', 'Phường Đội Cấn', 'Nhà riêng', '2024-01-10', '2024-02-15'),
(2, 1, 'Hà Nội', 'Cầu Giấy', '456 Xuân Thủy', 'Phường Dịch Vọng', 'Công ty', '2024-02-01', '2024-02-20'),
(3, 2, 'Hồ Chí Minh', 'Quận 1', '789 Lê Lợi', 'Phường Bến Nghé', 'Nhà riêng', '2024-01-20', '2024-02-25'),
(4, 3, 'Đà Nẵng', 'Hải Châu', '99 Nguyễn Văn Linh', 'Phường Bình Hiên', 'Nhà riêng', '2024-02-05', '2024-02-28'),
(5, 4, 'Hải Phòng', 'Lê Chân', '23 Trần Nguyên Hãn', 'Phường Cát Dài', 'Nhà riêng', '2024-01-30', '2024-02-18');

-- --------------------------------------------------------

--
-- Table structure for table `brand`
--

CREATE TABLE `brand` (
  `id_brand` int(11) NOT NULL,
  `name_brand` varchar(255) NOT NULL,
  `info_brand` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `brand`
--

INSERT INTO `brand` (`id_brand`, `name_brand`, `info_brand`) VALUES
(1, 'Nike', 'Thương hiệu giày thể thao nổi tiếng từ Mỹ'),
(2, 'Adidas', 'Thương hiệu giày thể thao đến từ Đức'),
(3, 'Puma', 'Thương hiệu giày thể thao nổi tiếng của Đức'),
(4, 'Converse', 'Thương hiệu giày sneaker phổ biến toàn cầu');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id_user` int(11) NOT NULL,
  `id_variant` int(11) NOT NULL,
  `quantity` int(11) DEFAULT 1,
  `created_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id_category` int(11) NOT NULL,
  `name_category` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id_category`, `name_category`) VALUES
(1, 'Sneaker'),
(2, 'Giày da'),
(3, 'Giày trẻ em');

-- --------------------------------------------------------

--
-- Table structure for table `chitietrole`
--

CREATE TABLE `chitietrole` (
  `id_chitietrole` int(11) NOT NULL,
  `ten_chitietrole` varchar(100) NOT NULL,
  `mota` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chitietrole`
--

INSERT INTO `chitietrole` (`id_chitietrole`, `ten_chitietrole`, `mota`) VALUES
(1, 'Quản lý sản phẩm', 'Quyền quản lý sản phẩm'),
(2, 'Quản lý đơn hàng', 'Quyền xử lý đơn hàng'),
(3, 'Quản lý người dùng', 'Quyền quản lý tài khoản khách hàng');

-- --------------------------------------------------------

--
-- Table structure for table `color`
--

CREATE TABLE `color` (
  `id_color` int(11) NOT NULL,
  `color_name` varchar(50) DEFAULT NULL,
  `color_code` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `color`
--

INSERT INTO `color` (`id_color`, `color_name`, `color_code`) VALUES
(1, 'Black', '#000000'),
(2, 'White', '#FFFFFF'),
(3, 'Red', '#FF0000'),
(4, 'Blue', '#0000FF'),
(5, 'Green', '#008000'),
(6, 'Yellow', '#FFFF00'),
(7, 'Gray', '#808080'),
(8, 'Pink', '#FFC0CB'),
(9, 'Purple', '#800080'),
(10, 'Orange', '#FFA500');

-- --------------------------------------------------------

--
-- Table structure for table `image`
--

CREATE TABLE `image` (
  `id_image` int(11) NOT NULL,
  `imageUrl` varchar(255) DEFAULT NULL,
  `isPrimary` tinyint(1) NOT NULL DEFAULT 0,
  `id_product` int(11) NOT NULL,
  `id_variant` int(11) DEFAULT NULL,
  `id_color` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `image`
--

INSERT INTO `image` (`id_image`, `imageUrl`, `isPrimary`, `id_product`, `id_variant`, `id_color`) VALUES
(1, 'giay1_1.png', 1, 1, 1, 1),
(2, 'giay1_2.png', 0, 1, 2, 7),
(3, 'giay1_3.png', 1, 1, 3, 4),
(4, 'adidas_ultraboost_22_2.jpg', 0, 2, NULL, NULL),
(5, 'puma_velocity_nitro_2_1.jpg', 1, 3, NULL, NULL),
(6, 'puma_velocity_nitro_2_2.jpg', 0, 3, NULL, NULL),
(7, 'converse_chuck_taylor_1.jpg', 1, 4, NULL, NULL),
(8, 'converse_chuck_taylor_2.jpg', 0, 4, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `invoice`
--

CREATE TABLE `invoice` (
  `id_invoice` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `CustomerName` varchar(100) DEFAULT NULL,
  `CustomerPhone` varchar(20) DEFAULT NULL,
  `InvoiceDate` date DEFAULT NULL,
  `TotalAmount` decimal(15,2) DEFAULT NULL,
  `Status` varchar(50) DEFAULT NULL,
  `CustomerAddress` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `invoice`
--

INSERT INTO `invoice` (`id_invoice`, `id_user`, `CustomerName`, `CustomerPhone`, `InvoiceDate`, `TotalAmount`, `Status`, `CustomerAddress`) VALUES
(1, 1, 'Nguyễn Văn A', '0987654321', '2024-02-20', 6500000.00, 'Đang xử lý', '123 Đội Cấn, Ba Đình, Hà Nội'),
(2, 2, 'Trần Thị B', '0912345678', '2024-02-21', 4500000.00, 'Hoàn thành', '456 Xuân Thủy, Cầu Giấy, Hà Nội'),
(3, 3, 'Lê Văn C', '0908765432', '2024-02-22', 3000000.00, 'Đang giao', '789 Lê Lợi, Quận 1, TP.HCM');

-- --------------------------------------------------------

--
-- Table structure for table `invoicedetail`
--

CREATE TABLE `invoicedetail` (
  `id_invoice` int(11) NOT NULL,
  `id_variant` int(11) NOT NULL,
  `quantity` int(11) DEFAULT NULL,
  `sub_total` decimal(15,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `invoicedetail`
--

INSERT INTO `invoicedetail` (`id_invoice`, `id_variant`, `quantity`, `sub_total`) VALUES
(1, 1, 1, 3200000.00),
(1, 2, 1, 3300000.00),
(2, 3, 1, 4500000.00),
(3, 5, 1, 3000000.00);

-- --------------------------------------------------------

--
-- Table structure for table `line`
--

CREATE TABLE `line` (
  `id_line` int(11) NOT NULL,
  `name_category` varchar(255) NOT NULL,
  `id_category` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `line`
--

INSERT INTO `line` (`id_line`, `name_category`, `id_category`) VALUES
(1, 'Giày bé nữ', 3),
(2, 'Giày bé nam', 3),
(3, 'Giày da nữ', 2),
(4, 'Giày da nam', 2),
(5, 'Giày sneaker cổ thấp', 1),
(6, 'Giày sneaker cổ cao', 1);

-- --------------------------------------------------------

--
-- Table structure for table `phanrole`
--

CREATE TABLE `phanrole` (
  `id_role` int(11) NOT NULL,
  `id_chitietrole` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `phanrole`
--

INSERT INTO `phanrole` (`id_role`, `id_chitietrole`) VALUES
(1, 1),
(1, 2),
(1, 3),
(2, 2),
(3, 3);

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `id_product` int(11) NOT NULL,
  `name_product` varchar(255) NOT NULL,
  `id_line` int(11) NOT NULL,
  `id_brand` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `material` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `imageUrl` varchar(255) DEFAULT NULL,
  `releasedate` datetime DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`id_product`, `name_product`, `id_line`, `id_brand`, `description`, `material`, `price`, `imageUrl`, `releasedate`, `status`) VALUES
(1, 'Nike Air Zoom Pegasus 39', 5, 1, 'Giày chạy bộ chuyên nghiệp, nhẹ, êm', 'Vải dệt, Cao su', 3200000.00, 'giay2.png', '2023-08-15 00:00:00', 'Còn hàng'),
(2, 'Adidas Ultraboost 22', 6, 2, 'Giày chạy bộ với đế Boost êm ái', 'Vải Primeknit, Cao su', 4500000.00, 'giay3.png', '2023-07-10 00:00:00', 'Còn hàng'),
(3, 'Puma Velocity Nitro 2', 3, 3, 'Giày chạy bộ với công nghệ Nitro Foam', 'Vải lưới, Cao su', 3000000.00, 'giay4.png', '2023-06-20 00:00:00', 'Còn hàng'),
(4, 'Converse Chuck Taylor All Star', 3, 4, 'Giày sneaker cổ thấp, phong cách cổ điển', 'Canvas, Cao su', 1700000.00, 'converse_chuck_taylor.jpg', '2022-05-20 00:00:00', 'Còn hàng'),
(5, 'Nike Air Force 1', 2, 1, 'Giày sneaker cổ cao, thiết kế huyền thoại', 'Da tổng hợp, Cao su', 3500000.00, 'giay1.png', '2023-09-10 00:00:00', 'Còn hàng'),
(6, 'Adidas Forum Low', 2, 2, 'Giày sneaker cổ thấp, phong cách retro', 'Da, Cao su', 2800000.00, 'giay5.png', '2023-01-15 00:00:00', 'Còn hàng'),
(7, 'Puma Suede Classic', 3, 3, 'Giày sneaker cổ thấp, phong cách cổ điển', 'Da lộn, Cao su', 2200000.00, 'puma_suede_classic.jpg', '2022-11-10 00:00:00', 'Còn hàng'),
(8, 'Nike Court Vision Low', 4, 1, 'Giày da thể thao phong cách thanh lịch', 'Da tổng hợp, Cao su', 2500000.00, 'giay5.png', '2023-04-05 00:00:00', 'Còn hàng'),
(9, 'Converse One Star Pro', 3, 4, 'Giày trượt ván với thiết kế đơn giản', 'Da lộn, Cao su', 2600000.00, 'converse_one_star_pro.jpg', '2023-02-20 00:00:00', 'Còn hàng'),
(10, 'Nike Air Zoom Pegasus 40', 5, 1, 'Giày chạy bộ chuyên nghiệp, nhẹ, êm', 'Vải dệt, Cao su', 3300000.00, 'giay10.png', '2024-03-15 00:00:00', 'Còn hàng'),
(11, 'Adidas Ultraboost 23', 6, 2, 'Giày chạy bộ với đế Boost êm ái', 'Vải Primeknit, Cao su', 4600000.00, 'giay11.png', '2024-02-10 00:00:00', 'Còn hàng'),
(12, 'Puma Velocity Nitro 3', 3, 3, 'Giày chạy bộ với công nghệ Nitro Foam', 'Vải lưới, Cao su', 3100000.00, 'giay12.png', '2024-01-25 00:00:00', 'Còn hàng'),
(13, 'Converse Chuck Taylor High', 4, 4, 'Giày sneaker cổ cao, phong cách cổ điển', 'Canvas, Cao su', 1900000.00, 'converse_high.png', '2024-02-05 00:00:00', 'Còn hàng'),
(14, 'Nike Air Force 1 Low', 3, 1, 'Giày sneaker cổ thấp, thiết kế huyền thoại', 'Da tổng hợp, Cao su', 3600000.00, 'giay13.png', '2024-01-15 00:00:00', 'Còn hàng'),
(15, 'Adidas Forum Mid', 2, 2, 'Giày sneaker cổ trung, phong cách retro', 'Da, Cao su', 2900000.00, 'giay14.png', '2024-03-01 00:00:00', 'Còn hàng'),
(16, 'Puma Suede Vintage', 3, 3, 'Giày sneaker cổ điển, da lộn cao cấp', 'Da lộn, Cao su', 2300000.00, 'puma_suede_vintage.png', '2024-02-20 00:00:00', 'Còn hàng'),
(17, 'Nike Court Vision Mid', 4, 1, 'Giày da thể thao phong cách trẻ trung', 'Da tổng hợp, Cao su', 2700000.00, 'giay15.png', '2024-03-10 00:00:00', 'Còn hàng'),
(18, 'Converse One Star Low', 3, 4, 'Giày trượt ván với thiết kế đơn giản', 'Da lộn, Cao su', 2500000.00, 'converse_one_star_low.png', '2024-01-30 00:00:00', 'Còn hàng'),
(19, 'Nike Zoom Fly 5', 5, 1, 'Giày chạy bộ tốc độ cao, nhẹ, êm', 'Vải dệt, Cao su', 3800000.00, 'giay16.png', '2024-03-05 00:00:00', 'Còn hàng');

-- --------------------------------------------------------

--
-- Table structure for table `product_variant`
--

CREATE TABLE `product_variant` (
  `id_variant` int(11) NOT NULL,
  `id_product` int(11) NOT NULL,
  `id_size` int(11) NOT NULL,
  `id_color` int(11) NOT NULL,
  `quantity` int(11) DEFAULT NULL,
  `expired_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_variant`
--

INSERT INTO `product_variant` (`id_variant`, `id_product`, `id_size`, `id_color`, `quantity`, `expired_date`) VALUES
(1, 1, 2, 1, 0, '2026-12-31'),
(2, 1, 1, 1, 40, '2026-12-31'),
(3, 1, 4, 7, 30, '2026-12-31'),
(4, 2, 1, 4, 60, '2026-12-31'),
(5, 2, 5, 5, 45, '2026-12-31'),
(6, 2, 6, 6, 35, '2026-12-31'),
(7, 3, 7, 1, 20, '2026-12-31'),
(8, 3, 8, 2, 25, '2026-12-31'),
(9, 3, 9, 7, 30, '2026-12-31'),
(10, 4, 10, 8, 50, '2026-12-31'),
(11, 4, 2, 9, 60, '2026-12-31'),
(12, 5, 3, 1, 55, '2026-12-31'),
(13, 5, 4, 10, 35, '2026-12-31'),
(14, 6, 5, 2, 50, '2026-12-31'),
(15, 6, 6, 3, 40, '2026-12-31'),
(16, 7, 7, 4, 45, '2026-12-31'),
(17, 7, 8, 5, 30, '2026-12-31'),
(18, 8, 9, 6, 50, '2026-12-31'),
(19, 8, 10, 7, 40, '2026-12-31'),
(20, 9, 1, 9, 30, '2026-12-31'),
(21, 1, 3, 1, 1, '2026-12-31');

-- --------------------------------------------------------

--
-- Table structure for table `promotions`
--

CREATE TABLE `promotions` (
  `id_promotions` int(11) NOT NULL,
  `name_promotion` varchar(255) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `discount_type` varchar(50) DEFAULT NULL,
  `discount_value` decimal(5,2) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `promotions`
--

INSERT INTO `promotions` (`id_promotions`, `name_promotion`, `start_date`, `end_date`, `discount_type`, `discount_value`, `description`, `status`) VALUES
(1, 'Khuyến mãi Tết', '2025-01-01', '2025-02-01', 'percentage', 20.00, 'Giảm giá 20% cho tất cả sản phẩm nhân dịp Tết', 1),
(2, 'Giảm giá cuối năm', '2024-12-01', '2024-12-31', 'percentage', 15.00, 'Ưu đãi cuối năm giảm giá 15%', 1),
(3, 'Flash Sale Tháng 3', '2025-03-05', '2025-03-10', 'amount', 999.99, 'Giảm ngay 50,000 VND cho đơn hàng trên 500,000 VND', 1),
(4, 'Ưu đãi thành viên', '2025-04-01', '2025-04-30', 'percentage', 10.00, 'Giảm giá 10% cho thành viên VIP', 1);

-- --------------------------------------------------------

--
-- Table structure for table `promotions_product`
--

CREATE TABLE `promotions_product` (
  `id_promotion` int(11) NOT NULL,
  `id_product` int(11) NOT NULL,
  `promotion_price` decimal(10,2) NOT NULL CHECK (`promotion_price` >= 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `promotions_product`
--

INSERT INTO `promotions_product` (`id_promotion`, `id_product`, `promotion_price`) VALUES
(1, 1, 800000.00),
(1, 2, 960000.00),
(2, 3, 850000.00),
(3, 4, 700000.00),
(4, 5, 900000.00);

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `id_role` int(11) NOT NULL,
  `name_role` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`id_role`, `name_role`, `description`) VALUES
(1, 'Admin', 'Quản trị viên có toàn quyền quản lý hệ thống'),
(2, 'Nhân viên', 'Nhân viên cửa hàng, có quyền quản lý sản phẩm và đơn hàng'),
(3, 'Khách hàng', 'Người dùng mua sắm trên hệ thống');

-- --------------------------------------------------------

--
-- Table structure for table `size`
--

CREATE TABLE `size` (
  `id_size` int(11) NOT NULL,
  `size_value` decimal(10,0) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `size`
--

INSERT INTO `size` (`id_size`, `size_value`, `type`) VALUES
(1, 38, 'EU'),
(2, 39, 'EU'),
(3, 40, 'EU'),
(4, 41, 'EU'),
(5, 42, 'EU'),
(6, 7, 'US'),
(7, 8, 'US'),
(8, 9, 'US'),
(9, 10, 'US'),
(10, 11, 'US');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `fullname` varchar(255) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` int(11) DEFAULT NULL,
  `id_role` int(11) NOT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  `createdate` date DEFAULT NULL,
  `last_logindate` date DEFAULT NULL,
  `profileimage` varchar(255) DEFAULT NULL,
  `accesstoken` varchar(255) DEFAULT NULL,
  `refreshtoken` varchar(255) DEFAULT NULL,
  `email_value` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `username`, `password`, `fullname`, `email`, `phone`, `id_role`, `is_active`, `createdate`, `last_logindate`, `profileimage`, `accesstoken`, `refreshtoken`, `email_value`) VALUES
(1, 'admin123', '123', 'Nguyễn văn A', 'admin@example.com', 987654321, 1, 1, '2024-03-01', '2025-03-10', 'admin.jpg', NULL, NULL, 1),
(2, 'nhanvien01', 'hashedpassword2', 'Trần Thị B', 'staff@example.com', 987654322, 2, 1, '2024-03-05', '2025-03-09', 'staff.jpg', NULL, NULL, 1),
(3, 'khachhang01', 'hashedpassword3', 'Lê Hoàng C', 'customer@example.com', 987654323, 3, 1, '2024-02-20', '2025-03-08', 'customer.jpg', NULL, NULL, 1),
(4, 'khachhang02', 'hashedpassword4', 'Phạm Thị D', 'customer2@example.com', 987654324, 3, 1, '2024-02-25', '2025-03-07', 'customer2.jpg', NULL, NULL, 1),
(6, 'user123', '123', 'Bùi Thạch', 'thach3112003@gmail.com', 776711376, 2, 1, '2025-03-14', NULL, NULL, NULL, NULL, NULL),
(7, 'Buithach311', '123', 'Bùi Công Thạch', 'thach3112003@gmail.com', 776711376, 2, 1, '2025-03-24', NULL, NULL, NULL, NULL, NULL),
(8, 'admin', '$2y$10$bNabp8.Qw40/bclTnHLoO.QSmaBs2eWLZU0.fBxSuCGpA9Jo.bPL6', NULL, NULL, NULL, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(9, 'chungminh', '$2y$10$h8fNV5/ayXXNPUSqNDU7CehqtkpeqqPBsaN7lULmgwTjHdSDpNpCi', NULL, NULL, NULL, 2, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(10, '12345', '$2y$10$AO61xixKxcYuTE0xEy2xI.cOfxy9jPNR8JlYJuh/u/OkNib6WiCp6', NULL, NULL, NULL, 2, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(11, '123', '$2y$10$hXNR4P.3vrHLP3VH7X9.ROIzmml/5A/nEq3MAskrJLF9NgKrMSMOi', NULL, NULL, NULL, 2, 1, NULL, NULL, NULL, NULL, NULL, NULL),
(12, '1234', '$2y$10$YZybTBNaRkT8ZH4CVTZo7elErHPmNXxYreFX0gNNNEzozUpCroUyG', NULL, NULL, NULL, 2, 1, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `warranty`
--

CREATE TABLE `warranty` (
  `id_warranty` int(11) NOT NULL,
  `id_invoice` int(11) DEFAULT NULL,
  `id_variant` int(11) DEFAULT NULL,
  `warranty_start_date` date DEFAULT NULL,
  `warranty_end_date` date DEFAULT NULL,
  `warranty_status` varchar(50) DEFAULT NULL,
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `warranty`
--

INSERT INTO `warranty` (`id_warranty`, `id_invoice`, `id_variant`, `warranty_start_date`, `warranty_end_date`, `warranty_status`, `notes`) VALUES
(1, 1, 1, '2024-02-20', '2025-02-20', 'Đang hiệu lực', 'Bảo hành 1 năm'),
(2, 2, 3, '2023-02-21', '2025-02-21', 'Hết hạn', 'Hết thời gian bảo hành'),
(3, 3, 5, '2024-08-22', '2025-02-22', 'Đang hiệu lực', 'Sản phẩm vẫn trong thời gian bảo hành');

-- --------------------------------------------------------

--
-- Table structure for table `warrantydetail`
--

CREATE TABLE `warrantydetail` (
  `id_warrantydetail` int(11) NOT NULL,
  `id_warranty` int(11) DEFAULT NULL,
  `repair_date` date DEFAULT NULL,
  `repair_description` text DEFAULT NULL,
  `repair_status` varchar(50) DEFAULT NULL,
  `cost` decimal(15,2) DEFAULT NULL,
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `warrantydetail`
--

INSERT INTO `warrantydetail` (`id_warrantydetail`, `id_warranty`, `repair_date`, `repair_description`, `repair_status`, `cost`, `notes`) VALUES
(1, 1, '2024-05-10', 'Thay đế giày do bong tróc', 'Hoàn thành', 200000.00, 'Khách hàng không tốn phí do bảo hành'),
(2, 3, '2024-06-15', 'Sửa đường chỉ may bị đứt', 'Đang xử lý', 0.00, 'Đang trong quá trình sửa chữa'),
(3, 2, '2024-03-05', 'Kiểm tra keo dán giày', 'Hoàn thành', 0.00, 'Sản phẩm còn tốt, không cần sửa chữa');

-- --------------------------------------------------------

--
-- Table structure for table `__efmigrationshistory`
--

CREATE TABLE `__efmigrationshistory` (
  `MigrationId` varchar(150) NOT NULL,
  `ProductVersion` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `__efmigrationshistory`
--

INSERT INTO `__efmigrationshistory` (`MigrationId`, `ProductVersion`) VALUES
('20250312103048_InitDatabase', '8.0.13');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `address`
--
ALTER TABLE `address`
  ADD PRIMARY KEY (`id_address`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `brand`
--
ALTER TABLE `brand`
  ADD PRIMARY KEY (`id_brand`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id_user`,`id_variant`),
  ADD KEY `id_variant` (`id_variant`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id_category`);

--
-- Indexes for table `chitietrole`
--
ALTER TABLE `chitietrole`
  ADD PRIMARY KEY (`id_chitietrole`);

--
-- Indexes for table `color`
--
ALTER TABLE `color`
  ADD PRIMARY KEY (`id_color`);

--
-- Indexes for table `image`
--
ALTER TABLE `image`
  ADD PRIMARY KEY (`id_image`),
  ADD KEY `fk_image_product` (`id_product`),
  ADD KEY `fk_image_variant` (`id_variant`),
  ADD KEY `fk_image_color` (`id_color`);

--
-- Indexes for table `invoice`
--
ALTER TABLE `invoice`
  ADD PRIMARY KEY (`id_invoice`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `invoicedetail`
--
ALTER TABLE `invoicedetail`
  ADD PRIMARY KEY (`id_invoice`,`id_variant`),
  ADD KEY `id_variant` (`id_variant`);

--
-- Indexes for table `line`
--
ALTER TABLE `line`
  ADD PRIMARY KEY (`id_line`),
  ADD KEY `id_category` (`id_category`);

--
-- Indexes for table `phanrole`
--
ALTER TABLE `phanrole`
  ADD PRIMARY KEY (`id_role`,`id_chitietrole`),
  ADD KEY `id_chitietrole` (`id_chitietrole`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id_product`),
  ADD KEY `id_line` (`id_line`),
  ADD KEY `id_brand` (`id_brand`);

--
-- Indexes for table `product_variant`
--
ALTER TABLE `product_variant`
  ADD PRIMARY KEY (`id_variant`),
  ADD KEY `id_product` (`id_product`),
  ADD KEY `id_size` (`id_size`),
  ADD KEY `id_color` (`id_color`);

--
-- Indexes for table `promotions`
--
ALTER TABLE `promotions`
  ADD PRIMARY KEY (`id_promotions`);

--
-- Indexes for table `promotions_product`
--
ALTER TABLE `promotions_product`
  ADD PRIMARY KEY (`id_promotion`,`id_product`),
  ADD KEY `id_product` (`id_product`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id_role`);

--
-- Indexes for table `size`
--
ALTER TABLE `size`
  ADD PRIMARY KEY (`id_size`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`),
  ADD KEY `id_role` (`id_role`);

--
-- Indexes for table `warranty`
--
ALTER TABLE `warranty`
  ADD PRIMARY KEY (`id_warranty`),
  ADD KEY `id_invoice` (`id_invoice`),
  ADD KEY `id_variant` (`id_variant`);

--
-- Indexes for table `warrantydetail`
--
ALTER TABLE `warrantydetail`
  ADD PRIMARY KEY (`id_warrantydetail`),
  ADD KEY `id_warranty` (`id_warranty`);

--
-- Indexes for table `__efmigrationshistory`
--
ALTER TABLE `__efmigrationshistory`
  ADD PRIMARY KEY (`MigrationId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `address`
--
ALTER TABLE `address`
  MODIFY `id_address` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `brand`
--
ALTER TABLE `brand`
  MODIFY `id_brand` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id_category` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `chitietrole`
--
ALTER TABLE `chitietrole`
  MODIFY `id_chitietrole` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `color`
--
ALTER TABLE `color`
  MODIFY `id_color` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `image`
--
ALTER TABLE `image`
  MODIFY `id_image` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `invoice`
--
ALTER TABLE `invoice`
  MODIFY `id_invoice` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `line`
--
ALTER TABLE `line`
  MODIFY `id_line` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `id_product` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `product_variant`
--
ALTER TABLE `product_variant`
  MODIFY `id_variant` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `promotions`
--
ALTER TABLE `promotions`
  MODIFY `id_promotions` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `id_role` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `size`
--
ALTER TABLE `size`
  MODIFY `id_size` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `warranty`
--
ALTER TABLE `warranty`
  MODIFY `id_warranty` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `warrantydetail`
--
ALTER TABLE `warrantydetail`
  MODIFY `id_warrantydetail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `address`
--
ALTER TABLE `address`
  ADD CONSTRAINT `address_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE;

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`id_variant`) REFERENCES `product_variant` (`id_variant`) ON DELETE CASCADE;

--
-- Constraints for table `image`
--
ALTER TABLE `image`
  ADD CONSTRAINT `fk_image_color` FOREIGN KEY (`id_color`) REFERENCES `color` (`id_color`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_image_product` FOREIGN KEY (`id_product`) REFERENCES `product` (`id_product`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_image_variant` FOREIGN KEY (`id_variant`) REFERENCES `product_variant` (`id_variant`) ON DELETE CASCADE;

--
-- Constraints for table `invoice`
--
ALTER TABLE `invoice`
  ADD CONSTRAINT `invoice_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`);

--
-- Constraints for table `invoicedetail`
--
ALTER TABLE `invoicedetail`
  ADD CONSTRAINT `invoicedetail_ibfk_1` FOREIGN KEY (`id_invoice`) REFERENCES `invoice` (`id_invoice`),
  ADD CONSTRAINT `invoicedetail_ibfk_2` FOREIGN KEY (`id_variant`) REFERENCES `product_variant` (`id_variant`);

--
-- Constraints for table `line`
--
ALTER TABLE `line`
  ADD CONSTRAINT `line_ibfk_1` FOREIGN KEY (`id_category`) REFERENCES `category` (`id_category`) ON DELETE CASCADE;

--
-- Constraints for table `phanrole`
--
ALTER TABLE `phanrole`
  ADD CONSTRAINT `phanrole_ibfk_1` FOREIGN KEY (`id_role`) REFERENCES `role` (`id_role`),
  ADD CONSTRAINT `phanrole_ibfk_2` FOREIGN KEY (`id_chitietrole`) REFERENCES `chitietrole` (`id_chitietrole`);

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_ibfk_1` FOREIGN KEY (`id_line`) REFERENCES `line` (`id_line`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_ibfk_2` FOREIGN KEY (`id_brand`) REFERENCES `brand` (`id_brand`) ON DELETE CASCADE;

--
-- Constraints for table `product_variant`
--
ALTER TABLE `product_variant`
  ADD CONSTRAINT `product_variant_ibfk_1` FOREIGN KEY (`id_product`) REFERENCES `product` (`id_product`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_variant_ibfk_2` FOREIGN KEY (`id_size`) REFERENCES `size` (`id_size`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_variant_ibfk_3` FOREIGN KEY (`id_color`) REFERENCES `color` (`id_color`) ON DELETE CASCADE;

--
-- Constraints for table `promotions_product`
--
ALTER TABLE `promotions_product`
  ADD CONSTRAINT `promotions_product_ibfk_1` FOREIGN KEY (`id_promotion`) REFERENCES `promotions` (`id_promotions`) ON DELETE CASCADE,
  ADD CONSTRAINT `promotions_product_ibfk_2` FOREIGN KEY (`id_product`) REFERENCES `product` (`id_product`) ON DELETE CASCADE;

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`id_role`) REFERENCES `role` (`id_role`) ON DELETE CASCADE;

--
-- Constraints for table `warranty`
--
ALTER TABLE `warranty`
  ADD CONSTRAINT `warranty_ibfk_1` FOREIGN KEY (`id_invoice`) REFERENCES `invoice` (`id_invoice`),
  ADD CONSTRAINT `warranty_ibfk_2` FOREIGN KEY (`id_variant`) REFERENCES `product_variant` (`id_variant`);

--
-- Constraints for table `warrantydetail`
--
ALTER TABLE `warrantydetail`
  ADD CONSTRAINT `warrantydetail_ibfk_1` FOREIGN KEY (`id_warranty`) REFERENCES `warranty` (`id_warranty`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
