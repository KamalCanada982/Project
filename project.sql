-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 14, 2024 at 06:28 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `project`
--

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `book_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `publication_year` int(4) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`book_id`, `title`, `author`, `publication_year`, `price`, `image`) VALUES
(54, 'To Kill a Mockingbird', 'Harper Lee', 1960, 10.99, NULL),
(55, '1984', 'George Orwell', 1949, 13.45, NULL),
(56, 'Pride and Prejudice', 'Jane Austen', 1813, 8.99, NULL),
(57, 'The Great Gatsby', 'F. Scott Fitzgerald', 1925, 11.49, NULL),
(58, 'Moby Dick', 'Herman Melville', 1851, 9.50, NULL),
(59, 'The Catcher in the Rye', 'J.D. Salinger', 1951, 10.75, NULL),
(60, 'War and Peace', 'Leo Tolstoy', 1869, 14.99, NULL),
(61, 'Crime and Punishment', 'Fyodor Dostoevsky', 1866, 13.99, NULL),
(62, 'The Hobbit', 'J.R.R. Tolkien', 1937, 15.99, NULL),
(63, 'The Alchemist', 'Paulo Coelho', 1988, 11.99, NULL),
(64, 'Brave New World', 'Aldous Huxley', 1932, 13.50, NULL),
(65, 'Les Misérables', 'Victor Hugo', 1862, 16.49, NULL),
(66, 'The Adventures of Huckleberry Finn', 'Mark Twain', 1884, 9.99, NULL),
(67, 'Anna Karenina', 'Leo Tolstoy', 1877, 14.25, NULL),
(68, 'The Count of Monte Cristo', 'Alexandre Dumas', 1844, 17.99, NULL),
(69, 'The Road', 'Cormac McCarthy', 2006, 12.75, NULL),
(70, 'The Book Thief', 'Markus Zusak', 2005, 11.99, NULL),
(71, 'Little Women', 'Louisa May Alcott', 1868, 9.50, NULL),
(72, 'The Giver', 'Lois Lowry', 1993, 10.25, NULL),
(73, 'Life of Pi', 'Yann Martel', 2001, 12.49, NULL),
(74, 'The Kite Runner', 'Khaled Hosseini', 2003, 13.99, NULL),
(75, 'Fahrenheit 451', 'Ray Bradbury', 1953, 11.75, NULL),
(76, 'Jane Eyre', 'Charlotte Brontë', 1847, 10.99, NULL),
(77, 'The Lord of the Rings', 'J.R.R. Tolkien', 1954, 20.00, NULL),
(78, 'The Chronicles of Narnia', 'C.S. Lewis', 1950, 18.75, NULL),
(79, 'The Secret Garden', 'Frances Hodgson Burnett', 1911, 9.99, NULL),
(80, 'Dracula', 'Bram Stoker', 1897, 12.49, NULL),
(81, 'Wuthering Heights', 'Emily Brontë', 1847, 10.99, NULL),
(82, 'The Picture of Dorian Gray', 'Oscar Wilde', 1890, 11.99, NULL),
(83, 'The Shining', 'Stephen King', 1977, 13.75, NULL),
(84, 'It', 'Stephen King', 1986, 15.99, NULL),
(85, 'Misery', 'Stephen King', 1987, 12.99, NULL),
(86, 'The Hunger Games', 'Suzanne Collins', 2008, 11.99, NULL),
(87, 'Catching Fire', 'Suzanne Collins', 2009, 11.99, NULL),
(88, 'Mockingjay', 'Suzanne Collins', 2010, 12.49, NULL),
(89, 'Dune', 'Frank Herbert', 1965, 13.99, NULL),
(90, 'A Game of Thrones', 'George R.R. Martin', 1996, 14.99, NULL),
(91, 'A Clash of Kings', 'George R.R. Martin', 1998, 15.49, NULL),
(93, 'The Fault in Our Stars', 'John Green', 2012, 10.99, NULL),
(94, 'Paper Towns', 'John Green', 2008, 9.99, NULL),
(95, 'Looking for Alaska', 'John Green', 2005, 8.75, NULL),
(96, 'Gone Girl', 'Gillian Flynn', 2012, 11.99, NULL),
(97, 'Sharp Objects', 'Gillian Flynn', 2006, 10.49, NULL),
(98, 'Dark Places', 'Gillian Flynn', 2009, 10.99, NULL),
(99, 'The Girl with the Dragon Tattoo', 'Stieg Larsson', 2005, 12.99, NULL),
(100, 'The Girl Who Played with Fire', 'Stieg Larsson', 2006, 13.49, NULL),
(101, 'The Girl Who Kicked the Hornet’s Nest', 'Stieg Larsson', 2007, 14.75, NULL),
(102, 'The Help', 'Kathryn Stockett', 2009, 11.99, NULL),
(103, 'The Night Circus', 'Erin Morgenstern', 2011, 12.99, NULL),
(106, 'A Test Book', 'kamal', 2000, 2000.00, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `comment_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`comment_id`, `book_id`, `user_id`, `content`, `created_at`) VALUES
(5, 91, 6, 'george di kamlai\r\n', '2024-12-09 23:23:26');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','editor','registered','guest') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `email`, `username`, `password`, `role`) VALUES
(4, '', 'kaur_sidhu12316', '$2y$10$ISQZPgzBsG2rfXQaNpMW0.4HPJH6OpqIy8mepD51PnDviH6S4AT8O', 'registered'),
(6, '', 'admin', '$2y$10$mC.ze81NXYmMwWjPvj3O4OxLxqX5SiVm5yYdaZ2cOnWCdHpzXREAi', 'admin'),
(7, '', 'admin2', '$2y$10$Tyo/7nkp3KV5GmKwFkNWkez7lE67zWicFIX1thLuXsUtiqTZk3jSe', 'admin'),
(8, 'kamaljeetkaurcanada982@gmail.com', 'Kamal', '$2y$10$e4OaC1H1QC63yX5LLKy0eOo/0mUQJrYTStO8qtER6R277WnLexoU2', 'registered');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`book_id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `book_id` (`book_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `book_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=107;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `books` (`book_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
