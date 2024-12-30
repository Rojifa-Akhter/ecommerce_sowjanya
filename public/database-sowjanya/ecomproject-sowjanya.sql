-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 30, 2024 at 10:06 AM
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
-- Database: `ecomproject-sowjanya`
--

-- --------------------------------------------------------

--
-- Table structure for table `abouts`
--

CREATE TABLE `abouts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` longtext NOT NULL,
  `image` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`image`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `abouts`
--

INSERT INTO `abouts` (`id`, `title`, `description`, `image`, `created_at`, `updated_at`) VALUES
(1, 'About Us', 'Ever bought a shirt, loved it, wore it once, and—bam!—you spot three other people wearing the exact same thing? Or maybe it looked great in the store, but after two washes, it turned into something your cat wouldn’t sleep on. Yeah, we’ve been there too. At Alder and Rhodes, we got tired of fashion that falls apart faster than your weekend plans. We wanted clothes that didn’t just look good, but felt good, lasted longer, and—most importantly—stood out. Because nothing ruins a vibe like seeing your “unique” outfit at every other table in the pub. So, we did something about it. We create limited collections crafted with care, precision, and premium materials. Our drops are rare, because we believe your style should be yours, not everyone else’s. Clothes that are soft, comfortable, and made to last—wash after wash, wear after wear. Think of us as the antidote to fast fashion: timeless style, effortless elegance, and pieces that make you feel like you. Not a trend-follower. Not a number. Just someone who values quality, individuality, and a bit of exclusivity. Because let’s face it—clothes should feel special. And you? You deserve the kind of style that people notice, ask about, and never forget. Alder and Rhodes: Limited pieces for unlimited confidence.', '\"[\\\"http:\\\\\\/\\\\\\/10.0.80.16:8000\\\\\\/storage\\\\\\/about_images\\\\\\/rFPVK8GWkFUpzUJLmdkhiGtif9RUljKP398EJAoQ.jpg\\\",\\\"http:\\\\\\/\\\\\\/10.0.80.16:8000\\\\\\/storage\\\\\\/about_images\\\\\\/AuLMHm1d3dj8501d6096oOgn34Tv4C7dDT9KSaMF.jpg\\\"]\"', '2024-12-28 18:11:27', '2024-12-30 15:01:51');

-- --------------------------------------------------------

--
-- Table structure for table `blogs`
--

CREATE TABLE `blogs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` longtext NOT NULL,
  `image` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`image`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `blogs`
--

INSERT INTO `blogs` (`id`, `title`, `description`, `image`, `created_at`, `updated_at`) VALUES
(1, 'Dr.', 'Exercitationem reprehenderit optio voluptatem excepturi. In et qui exercitationem sit et.', '\"https:\\/\\/via.placeholder.com\\/640x480.png\\/005566?text=animi\"', '2024-12-28 18:07:07', '2024-12-28 18:07:07'),
(2, 'Miss', 'Et id beatae rerum et et repudiandae nulla. Error porro quidem asperiores pariatur. Modi ducimus dolor quia illum quibusdam. Rerum sunt ipsa earum porro eos itaque est.', '\"https:\\/\\/via.placeholder.com\\/640x480.png\\/00aaaa?text=ipsa\"', '2024-12-28 18:07:07', '2024-12-28 18:07:07'),
(3, 'Mr.', 'Error non odio perspiciatis repellat. Architecto odio quam cumque et et aut consequuntur. Dolorum sed recusandae eum assumenda. Ab dignissimos tempora quos. Enim aut numquam ducimus molestias facilis nesciunt.', '\"http:\\/\\/10.0.80.16:8000\\/storage\\/blog_images\\/edSLbVOUUmyrUV68OGuISfqzBFI7lSN602E0UI6E.png\"', '2024-12-28 18:07:07', '2024-12-28 18:09:40'),
(4, 'Mrs.', 'Enim eaque mollitia explicabo. Aut magnam explicabo consequatur enim nesciunt voluptatem excepturi. Optio quibusdam consequatur et magni dolore. Repellendus itaque dignissimos eum quos. Ut voluptates animi non.', '\"https:\\/\\/via.placeholder.com\\/640x480.png\\/0066ee?text=ut\"', '2024-12-28 18:07:07', '2024-12-28 18:07:07'),
(5, 'Mrs.', 'Voluptate provident et tempore distinctio doloremque. Ipsum labore rem assumenda maiores et voluptatem architecto. Non vitae sunt dolor eum voluptatem deserunt. Voluptatem suscipit a qui ipsa est ut praesentium.', '\"https:\\/\\/via.placeholder.com\\/640x480.png\\/00dd33?text=odit\"', '2024-12-28 18:07:07', '2024-12-28 18:07:07'),
(6, 'Mrs.', 'Quisquam libero earum quidem labore velit similique omnis non. Quo omnis quia ut tempore. Exercitationem iure culpa ea provident. In incidunt odio dolores enim nulla distinctio cumque.', '\"https:\\/\\/via.placeholder.com\\/640x480.png\\/007700?text=rerum\"', '2024-12-28 18:07:07', '2024-12-28 18:07:07'),
(7, 'Mr.', 'Deserunt eaque aperiam perferendis consequatur quis maxime. Laudantium est et voluptas rerum sequi et qui.', '\"https:\\/\\/via.placeholder.com\\/640x480.png\\/00aabb?text=dolorem\"', '2024-12-28 18:07:07', '2024-12-28 18:07:07'),
(8, 'Prof.', 'Doloremque autem pariatur amet quia vitae molestias voluptas. Natus et totam libero quibusdam quisquam amet. Reprehenderit aut ut tempora rerum rerum est animi sunt.', '\"https:\\/\\/via.placeholder.com\\/640x480.png\\/008888?text=facere\"', '2024-12-28 18:07:07', '2024-12-28 18:07:07'),
(9, 'Mr.', 'Nulla qui occaecati enim. Quae fuga et dolorum molestiae eos quia est. Aut numquam aut dolor pariatur.', '\"https:\\/\\/via.placeholder.com\\/640x480.png\\/006622?text=sint\"', '2024-12-28 18:07:07', '2024-12-28 18:07:07'),
(10, 'Mr.', 'Quasi delectus corporis illo et exercitationem rerum dolor. Officia ea sequi quis deleniti. Explicabo et nostrum possimus. At et corrupti consequatur ut temporibus adipisci repellat. Modi ullam eius enim.', '\"https:\\/\\/via.placeholder.com\\/640x480.png\\/00aaff?text=animi\"', '2024-12-28 18:07:07', '2024-12-28 18:07:07'),
(11, 'Miss', 'Consequatur nostrum odio corporis. Aspernatur quam suscipit unde dolores harum.', '\"https:\\/\\/via.placeholder.com\\/640x480.png\\/00cc99?text=voluptate\"', '2024-12-28 18:07:07', '2024-12-28 18:07:07'),
(12, 'Mrs.', 'Quae sit alias ea quia. Et quas voluptatum odit et et quia. Eius voluptate qui esse et exercitationem. Id error eligendi cumque eius nostrum ut accusamus. Deserunt esse nulla quae et sapiente aut excepturi.', '\"https:\\/\\/via.placeholder.com\\/640x480.png\\/004400?text=sunt\"', '2024-12-28 18:07:07', '2024-12-28 18:07:07'),
(13, 'Dr.', 'Quod et iure ipsa ut. In eius occaecati maiores ipsum voluptas quidem magnam incidunt. Animi incidunt ad ut suscipit nihil necessitatibus commodi. Eligendi animi molestias animi optio minus esse aut esse.', '\"https:\\/\\/via.placeholder.com\\/640x480.png\\/0011dd?text=aspernatur\"', '2024-12-28 18:07:07', '2024-12-28 18:07:07'),
(14, 'Dr.', 'Et voluptas eos aliquid quae non laborum neque non. Nulla voluptate minima commodi quia et earum ut. Consequatur quisquam itaque aliquam quod autem. Omnis sed magnam eveniet consequatur consectetur et.', '\"https:\\/\\/via.placeholder.com\\/640x480.png\\/000066?text=nulla\"', '2024-12-28 18:07:07', '2024-12-28 18:07:07'),
(15, 'Prof.', 'Aut aut voluptas dolorem et. Totam alias consequatur labore accusamus hic. Beatae ut molestias recusandae nostrum ipsam.', '\"https:\\/\\/via.placeholder.com\\/640x480.png\\/00ccaa?text=ullam\"', '2024-12-28 18:07:07', '2024-12-28 18:07:07'),
(16, 'Miss', 'Vel occaecati ut rerum sed minima eius ipsum. Quis molestiae magnam optio quo accusantium voluptatem. Recusandae earum consequuntur fugit recusandae necessitatibus et distinctio aut. Molestias sed numquam sint ipsum.', '\"https:\\/\\/via.placeholder.com\\/640x480.png\\/00ee11?text=sed\"', '2024-12-28 18:07:07', '2024-12-28 18:07:07'),
(17, 'Mr.', 'Ea ratione ab quaerat rerum. Velit molestiae quaerat dolor dolorum voluptatum id doloremque. Fuga error ad consequuntur eos nesciunt quam et.', '\"https:\\/\\/via.placeholder.com\\/640x480.png\\/005577?text=sint\"', '2024-12-28 18:07:07', '2024-12-28 18:07:07'),
(18, 'Prof.', 'Et officiis est reprehenderit assumenda ipsam. Cum ullam minus quo quia et. Rerum esse expedita qui. Et sed vitae corrupti provident veniam blanditiis.', '\"https:\\/\\/via.placeholder.com\\/640x480.png\\/0066ff?text=sequi\"', '2024-12-28 18:07:07', '2024-12-28 18:07:07'),
(19, 'Dr.', 'Neque aut sunt architecto et. Quae in laborum modi tempore dolores illo. Nesciunt molestiae error et porro.', '\"https:\\/\\/via.placeholder.com\\/640x480.png\\/0088cc?text=neque\"', '2024-12-28 18:07:07', '2024-12-28 18:07:07'),
(20, 'Dr.', 'Recusandae quia sit rerum harum quod minus pariatur. Perspiciatis at minima eligendi id non eos praesentium fugiat. Vitae expedita et veniam laudantium repellat aliquid.', '\"https:\\/\\/via.placeholder.com\\/640x480.png\\/00dd99?text=qui\"', '2024-12-28 18:07:07', '2024-12-28 18:07:07'),
(21, 'Mrs.', 'Qui optio aut magnam minima tempore sint ut. Optio numquam nemo velit aut dicta illo est. Molestiae quia et quis similique perspiciatis. Ut sint est magni qui similique sit quisquam.', '\"https:\\/\\/via.placeholder.com\\/640x480.png\\/007788?text=enim\"', '2024-12-28 18:07:07', '2024-12-28 18:07:07'),
(22, 'Mrs.', 'Expedita consectetur occaecati dolorum et et. Labore sed nemo laborum. Dolores sit ducimus autem quibusdam temporibus. Quo ipsam quos doloremque culpa doloremque.', '\"https:\\/\\/via.placeholder.com\\/640x480.png\\/00dd99?text=pariatur\"', '2024-12-28 18:07:07', '2024-12-28 18:07:07'),
(23, 'Prof.', 'Illo enim tempora hic reiciendis illum nesciunt enim. Voluptatem enim ut eligendi dolores animi. Necessitatibus ut est animi ut voluptas voluptas corporis adipisci.', '\"https:\\/\\/via.placeholder.com\\/640x480.png\\/008877?text=non\"', '2024-12-28 18:07:07', '2024-12-28 18:07:07'),
(24, 'Prof.', 'Similique doloribus similique qui aut eum nihil earum. Expedita temporibus tenetur quas laborum ipsum.', '\"https:\\/\\/via.placeholder.com\\/640x480.png\\/00bbcc?text=qui\"', '2024-12-28 18:07:07', '2024-12-28 18:07:07'),
(25, 'Prof.', 'Porro deleniti quo et sunt illum suscipit aut. Molestias pariatur unde nulla non totam. Nemo qui odit ut. Asperiores rerum aut voluptatem totam autem voluptas. Cum autem velit suscipit.', '\"https:\\/\\/via.placeholder.com\\/640x480.png\\/006622?text=neque\"', '2024-12-28 18:07:07', '2024-12-28 18:07:07'),
(26, 'Prof.', 'Ut velit omnis dolores est sunt doloremque quam. Sequi laudantium molestiae ratione nemo pariatur impedit qui. Nisi neque quia quasi non unde. Molestias ut maiores aut rerum perferendis exercitationem dignissimos. Quod et vel accusantium porro libero est qui dolor.', '\"https:\\/\\/via.placeholder.com\\/640x480.png\\/009988?text=voluptatem\"', '2024-12-28 18:07:07', '2024-12-28 18:07:07'),
(27, 'Prof.', 'Placeat dolorem et sed est reprehenderit vitae. Cum voluptatem nihil deserunt quis nisi autem non nam. Voluptatem quo sed quo non consequuntur voluptate beatae.', '\"https:\\/\\/via.placeholder.com\\/640x480.png\\/001166?text=debitis\"', '2024-12-28 18:07:07', '2024-12-28 18:07:07'),
(28, 'Dr.', 'Reiciendis quod distinctio et minima ab est. Laborum libero quia atque minima ea dolore recusandae. Dolorem ipsam optio exercitationem omnis fugiat. Facilis ut voluptatum cumque laboriosam.', '\"https:\\/\\/via.placeholder.com\\/640x480.png\\/000000?text=velit\"', '2024-12-28 18:07:07', '2024-12-28 18:07:07'),
(29, 'Prof.', 'Dolore laudantium impedit provident tenetur doloremque. Ut earum at autem est ut. Itaque dolores laudantium dolores.', '\"https:\\/\\/via.placeholder.com\\/640x480.png\\/00dd88?text=ad\"', '2024-12-28 18:07:07', '2024-12-28 18:07:07'),
(30, 'Dr.', 'Assumenda dignissimos qui facere et explicabo aut nobis. Facilis omnis magnam eos aliquid et. Fugit nisi quia qui reprehenderit. Sint possimus quam rerum.', '\"https:\\/\\/via.placeholder.com\\/640x480.png\\/00aabb?text=incidunt\"', '2024-12-28 18:07:07', '2024-12-28 18:07:07'),
(31, 'Prof.', 'Culpa minus est id ratione. Culpa illum impedit cumque quibusdam officia reprehenderit rerum. Quasi quas veniam nobis modi quo.', '\"https:\\/\\/via.placeholder.com\\/640x480.png\\/005533?text=minus\"', '2024-12-28 18:07:07', '2024-12-28 18:07:07'),
(32, 'Mr.', 'Ipsam autem laborum magnam voluptatem. Illum facilis debitis corrupti aliquid natus aliquam rerum.', '\"https:\\/\\/via.placeholder.com\\/640x480.png\\/00ffbb?text=ducimus\"', '2024-12-28 18:07:07', '2024-12-28 18:07:07'),
(33, 'Dr.', 'Aut ut fugiat ut aut. Non sunt voluptas blanditiis at corporis et.', '\"https:\\/\\/via.placeholder.com\\/640x480.png\\/002233?text=similique\"', '2024-12-28 18:07:07', '2024-12-28 18:07:07'),
(34, 'Ms.', 'Ut ab nihil officia laudantium laudantium quis nobis. Dolorem sint eum atque sit. Maiores fugiat ut ut laudantium impedit consequatur commodi.', '\"https:\\/\\/via.placeholder.com\\/640x480.png\\/0077ee?text=rerum\"', '2024-12-28 18:07:07', '2024-12-28 18:07:07'),
(35, 'Dr.', 'Labore qui dignissimos quaerat modi optio. Sed consequatur distinctio earum nobis dignissimos qui delectus. Voluptatum culpa omnis eum et ea est. Perferendis voluptatem voluptas voluptatibus pariatur animi autem. Ipsa ex ut est qui est iusto sed ab.', '\"https:\\/\\/via.placeholder.com\\/640x480.png\\/00dddd?text=sunt\"', '2024-12-28 18:07:07', '2024-12-28 18:07:07'),
(36, 'Prof.', 'Ut quia cumque esse. Modi adipisci ea nihil suscipit impedit dicta. Animi eius corporis modi aut. Totam et accusamus placeat dolore rerum et.', '\"https:\\/\\/via.placeholder.com\\/640x480.png\\/00dd99?text=magni\"', '2024-12-28 18:07:07', '2024-12-28 18:07:07'),
(37, 'Miss', 'Repellendus modi est laudantium. Laborum dolor quo aut magnam. Quis atque nesciunt impedit ducimus. Quod et molestias perferendis tempore voluptas et vero eum.', '\"https:\\/\\/via.placeholder.com\\/640x480.png\\/00cc33?text=quia\"', '2024-12-28 18:07:07', '2024-12-28 18:07:07'),
(38, 'Miss', 'Modi repudiandae accusamus et maxime quae dolores. Illo ad vel dolor. Omnis dolorum nulla id ab et. Minima qui ex dolore sapiente dolorem doloribus corrupti.', '\"https:\\/\\/via.placeholder.com\\/640x480.png\\/0099dd?text=facere\"', '2024-12-28 18:07:07', '2024-12-28 18:07:07'),
(39, 'Prof.', 'Explicabo dolorem nihil laboriosam laudantium natus. Reprehenderit provident nemo expedita fugit non consequatur. Beatae quia quasi qui consectetur.', '\"https:\\/\\/via.placeholder.com\\/640x480.png\\/0066dd?text=nihil\"', '2024-12-28 18:07:07', '2024-12-28 18:07:07'),
(40, 'Dr.', 'Impedit amet ratione sed veniam tempore inventore. Qui vero est sunt aperiam dicta. Est non ut officiis laborum. Error omnis cupiditate sit velit.', '\"https:\\/\\/via.placeholder.com\\/640x480.png\\/0022bb?text=maxime\"', '2024-12-28 18:07:07', '2024-12-28 18:07:07'),
(41, 'Prof.', 'Odio iusto autem recusandae odit sequi et. Quia est est nemo velit sed. Et omnis nam itaque omnis eius.', '\"https:\\/\\/via.placeholder.com\\/640x480.png\\/00ee77?text=dolore\"', '2024-12-28 18:07:07', '2024-12-28 18:07:07'),
(42, 'Ms.', 'Sit laboriosam dolor est aut. Et rem distinctio nihil rerum necessitatibus qui dolores. Expedita unde quos rem voluptatem aut occaecati aliquam nostrum. Cum et qui consequatur.', '\"https:\\/\\/via.placeholder.com\\/640x480.png\\/003333?text=perferendis\"', '2024-12-28 18:07:07', '2024-12-28 18:07:07'),
(43, 'Dr.', 'Magnam amet qui sit pariatur id non consequatur et. Explicabo cumque dolore dolorem et sint id deserunt quia. Omnis pariatur autem id. Sit officia ex distinctio in sint praesentium quo eaque. Blanditiis voluptatem animi enim sit quam assumenda alias.', '\"https:\\/\\/via.placeholder.com\\/640x480.png\\/0099aa?text=dolor\"', '2024-12-28 18:07:07', '2024-12-28 18:07:07'),
(44, 'Prof.', 'Eveniet corrupti tempora dignissimos voluptates. Assumenda repellendus occaecati odit ut harum.', '\"https:\\/\\/via.placeholder.com\\/640x480.png\\/00bb11?text=sed\"', '2024-12-28 18:07:07', '2024-12-28 18:07:07'),
(45, 'Dr.', 'Nisi tenetur nobis temporibus commodi eos. Minima ex autem quo officia doloremque modi neque. Sit exercitationem et minus sed illum consequuntur.', '\"https:\\/\\/via.placeholder.com\\/640x480.png\\/00dd00?text=tenetur\"', '2024-12-28 18:07:07', '2024-12-28 18:07:07'),
(46, 'Dr.', 'Nihil ipsa sunt ad voluptas deleniti fugiat deleniti beatae. In omnis corporis dolor qui. Quia cumque voluptas doloremque sequi perferendis nihil. Modi eum odio qui ut officia et et deserunt. Ex distinctio aliquid nostrum cum culpa.', '\"https:\\/\\/via.placeholder.com\\/640x480.png\\/001166?text=mollitia\"', '2024-12-28 18:07:07', '2024-12-28 18:07:07'),
(47, 'Prof.', 'Consequatur vitae consectetur quis enim molestiae. Praesentium omnis eveniet est eaque quam vitae voluptatem. Dignissimos sed sed culpa magnam beatae consequuntur. Aut nobis ipsum maiores quisquam sunt exercitationem.', '\"https:\\/\\/via.placeholder.com\\/640x480.png\\/003333?text=quaerat\"', '2024-12-28 18:07:07', '2024-12-28 18:07:07'),
(48, 'Ms.', 'Et totam temporibus neque aut qui modi exercitationem natus. Vel enim mollitia nostrum eos omnis a. Sed aut laudantium enim blanditiis tempora omnis. Voluptas earum occaecati ut quis voluptas veritatis.', '\"https:\\/\\/via.placeholder.com\\/640x480.png\\/00aaee?text=cumque\"', '2024-12-28 18:07:07', '2024-12-28 18:07:07'),
(49, 'Prof.', 'Enim recusandae rerum omnis est totam. Quae voluptates omnis sunt rerum quod. Fugit sit harum aut nihil. Voluptas officia sit et ea voluptatem quas ad harum.', '\"https:\\/\\/via.placeholder.com\\/640x480.png\\/0000ff?text=officiis\"', '2024-12-28 18:07:07', '2024-12-28 18:07:07'),
(50, 'Prof.', 'Est est quam consequatur rerum. Velit officia quasi vero est. Libero praesentium unde qui tempore ut doloribus consequatur rerum. Rem at voluptates delectus quia blanditiis libero aut.', '\"https:\\/\\/via.placeholder.com\\/640x480.png\\/00bb55?text=rerum\"', '2024-12-28 18:07:07', '2024-12-28 18:07:07'),
(51, 'Dr.', 'Modi sunt in nam eligendi. Blanditiis incidunt incidunt enim in nesciunt beatae. Expedita adipisci dolor vel.', '\"https:\\/\\/via.placeholder.com\\/640x480.png\\/008844?text=perferendis\"', '2024-12-28 18:07:07', '2024-12-28 18:07:07'),
(52, 'Prof.', 'Officiis eveniet suscipit a doloribus atque. Placeat maiores in aut veniam deserunt sint. Nihil nobis qui dolor et optio consequatur ut enim.', '\"https:\\/\\/via.placeholder.com\\/640x480.png\\/0044ee?text=aut\"', '2024-12-28 18:07:07', '2024-12-28 18:07:07'),
(53, 'Dr.', 'Voluptatum quidem voluptates qui ut ad eaque accusamus. Delectus voluptatem ut eos aut incidunt quia modi. Veritatis et ad autem.', '\"https:\\/\\/via.placeholder.com\\/640x480.png\\/006644?text=quasi\"', '2024-12-28 18:07:07', '2024-12-28 18:07:07'),
(54, 'Prof.', 'Laboriosam error in odit voluptatem non. Dicta vero repudiandae at voluptatem optio ea eos. Labore incidunt vel quam magni aliquam nisi. Consequuntur odio quis quaerat reiciendis. Illum ea in quaerat in.', '\"https:\\/\\/via.placeholder.com\\/640x480.png\\/00cc66?text=et\"', '2024-12-28 18:07:07', '2024-12-28 18:07:07'),
(55, 'Miss', 'Similique quia aut voluptas aspernatur est veritatis. Optio qui deserunt sed optio asperiores possimus quos. In sint expedita numquam culpa. Aliquid omnis nobis voluptas placeat necessitatibus quo.', '\"https:\\/\\/via.placeholder.com\\/640x480.png\\/009955?text=quisquam\"', '2024-12-28 18:07:07', '2024-12-28 18:07:07'),
(56, 'Mr.', 'Enim quod vel aut sunt doloribus ut commodi. Minus libero aperiam sed et perspiciatis sunt. Dolorem possimus et qui ea dicta possimus aut dolores. Perferendis magni quia qui esse.', '\"https:\\/\\/via.placeholder.com\\/640x480.png\\/000033?text=sapiente\"', '2024-12-28 18:07:07', '2024-12-28 18:07:07'),
(57, 'Prof.', 'Ad fuga ullam repellat illo. Qui alias atque voluptas repudiandae. Necessitatibus et unde occaecati tempore ab aut quis.', '\"https:\\/\\/via.placeholder.com\\/640x480.png\\/00dd00?text=consectetur\"', '2024-12-28 18:07:07', '2024-12-28 18:07:07'),
(58, 'Dr.', 'Quas aut corporis expedita sequi. Pariatur autem unde ut dolorum error id cum reprehenderit. Culpa repudiandae voluptatem nihil molestiae praesentium est accusantium facilis.', '\"https:\\/\\/via.placeholder.com\\/640x480.png\\/00eeff?text=autem\"', '2024-12-28 18:07:07', '2024-12-28 18:07:07'),
(59, 'Mrs.', 'Temporibus accusantium nulla mollitia magnam vel. Facere rem et est alias voluptatibus sed voluptatem quam. Libero voluptas sed reiciendis perspiciatis modi quisquam maiores. Quae aut est dolore non quia laborum.', '\"https:\\/\\/via.placeholder.com\\/640x480.png\\/006600?text=sed\"', '2024-12-28 18:07:07', '2024-12-28 18:07:07'),
(60, 'Mr.', 'Quis commodi ullam architecto rem aut magnam. Officia suscipit error nam reprehenderit accusamus quos. Ipsa tempora eligendi non dignissimos eligendi in.', '\"https:\\/\\/via.placeholder.com\\/640x480.png\\/00dd44?text=qui\"', '2024-12-28 18:07:07', '2024-12-28 18:07:07'),
(61, 'Mrs.', 'Omnis delectus dolorum debitis quibusdam nesciunt minus. Et libero cupiditate eius dolor. Voluptates aut laborum iure illum vero.', '\"https:\\/\\/via.placeholder.com\\/640x480.png\\/0088ff?text=ut\"', '2024-12-28 18:07:07', '2024-12-28 18:07:07'),
(62, 'Ms.', 'Nam laborum consequatur voluptatem. Qui nesciunt ullam beatae voluptatem aut fuga corrupti.', '\"https:\\/\\/via.placeholder.com\\/640x480.png\\/00ccaa?text=dolorem\"', '2024-12-28 18:07:07', '2024-12-28 18:07:07'),
(63, 'Dr.', 'Voluptas corrupti fugit quia nobis. Et hic et reiciendis eos. Qui autem temporibus voluptates libero repellat doloribus.', '\"https:\\/\\/via.placeholder.com\\/640x480.png\\/0000dd?text=sit\"', '2024-12-28 18:07:07', '2024-12-28 18:07:07'),
(64, 'Prof.', 'Esse aut nobis recusandae labore eum et. Et voluptates occaecati quibusdam cum. Eius ut ea soluta rerum.', '\"https:\\/\\/via.placeholder.com\\/640x480.png\\/00cc99?text=distinctio\"', '2024-12-28 18:07:07', '2024-12-28 18:07:07'),
(65, 'Mrs.', 'Occaecati adipisci minima at modi. Assumenda porro ad eaque sunt explicabo dolorem. Sint odio nihil cum sunt odit quibusdam autem. Eos fugiat molestiae neque omnis maxime tenetur libero.', '\"https:\\/\\/via.placeholder.com\\/640x480.png\\/009944?text=dolorem\"', '2024-12-28 18:07:07', '2024-12-28 18:07:07'),
(66, 'Ms.', 'Adipisci suscipit nam voluptas eveniet necessitatibus. Doloribus ad saepe quibusdam harum libero sed consequatur. Repellendus maxime saepe sequi architecto et adipisci rerum debitis.', '\"https:\\/\\/via.placeholder.com\\/640x480.png\\/00ccbb?text=rerum\"', '2024-12-28 18:07:07', '2024-12-28 18:07:07'),
(67, 'Dr.', 'Necessitatibus tenetur natus nobis accusamus. Ex sed neque atque et officia. Voluptas nemo animi pariatur omnis est quae aut. Aut dolorem fugit a iste.', '\"https:\\/\\/via.placeholder.com\\/640x480.png\\/0066cc?text=veritatis\"', '2024-12-28 18:07:07', '2024-12-28 18:07:07'),
(68, 'Ms.', 'Similique molestiae ipsa aliquid cupiditate aliquid ipsa illum ducimus. Suscipit tempora sed odit. Optio est ut dolores iste id voluptatum quidem. Non consectetur quisquam aperiam quia.', '\"https:\\/\\/via.placeholder.com\\/640x480.png\\/009999?text=perspiciatis\"', '2024-12-28 18:07:07', '2024-12-28 18:07:07'),
(69, 'Prof.', 'Praesentium sunt ut nam accusantium. Magnam fugiat quisquam maiores qui culpa commodi. Quia rerum id nobis ad delectus in.', '\"https:\\/\\/via.placeholder.com\\/640x480.png\\/0044bb?text=expedita\"', '2024-12-28 18:07:07', '2024-12-28 18:07:07'),
(70, 'Ms.', 'Minima nemo aut eum corporis qui temporibus. Sapiente magni rerum eveniet ipsum. Deserunt facere blanditiis labore odit fuga qui. In molestias temporibus voluptas voluptatibus.', '\"https:\\/\\/via.placeholder.com\\/640x480.png\\/004400?text=omnis\"', '2024-12-28 18:07:07', '2024-12-28 18:07:07'),
(71, 'Prof.', 'Atque voluptatibus consequuntur dolor iste qui tenetur blanditiis. Fugit ex voluptas voluptatem ut. Voluptatem dolores enim voluptatem possimus quia culpa. Aperiam quisquam minima optio qui eius earum.', '\"https:\\/\\/via.placeholder.com\\/640x480.png\\/00eeff?text=nesciunt\"', '2024-12-28 18:07:07', '2024-12-28 18:07:07'),
(72, 'Prof.', 'Et dolorem non enim rerum tenetur ducimus. Dolor autem illum architecto laudantium ab aliquid. Consequatur dolor dolor sequi necessitatibus fugit temporibus et. Similique recusandae ratione omnis mollitia veniam quis ab odit.', '\"https:\\/\\/via.placeholder.com\\/640x480.png\\/00ee55?text=ea\"', '2024-12-28 18:07:07', '2024-12-28 18:07:07'),
(73, 'Mrs.', 'Nostrum et aliquid sint nisi. In quia doloribus quo labore eveniet.', '\"https:\\/\\/via.placeholder.com\\/640x480.png\\/0044ee?text=repudiandae\"', '2024-12-28 18:07:07', '2024-12-28 18:07:07'),
(74, 'Dr.', 'Voluptatum nihil ut non esse nihil. Perferendis quo quis nihil sed et et omnis. Recusandae facilis ratione minus nihil. Corrupti quis omnis labore ut molestiae fugiat sit.', '\"https:\\/\\/via.placeholder.com\\/640x480.png\\/00dd00?text=provident\"', '2024-12-28 18:07:07', '2024-12-28 18:07:07'),
(75, 'Miss', 'Et praesentium aut explicabo sunt. Nobis rerum est quam molestias rerum.', '\"https:\\/\\/via.placeholder.com\\/640x480.png\\/001177?text=aut\"', '2024-12-28 18:07:07', '2024-12-28 18:07:07'),
(76, 'Prof.', 'Iste voluptatem voluptatem officia. Omnis velit nulla cumque soluta voluptas odio non. Expedita veritatis harum ipsam. Sint qui quo aut quod aliquid adipisci.', '\"https:\\/\\/via.placeholder.com\\/640x480.png\\/004477?text=voluptatum\"', '2024-12-28 18:07:07', '2024-12-28 18:07:07'),
(77, 'Prof.', 'Placeat harum esse odio incidunt. Temporibus voluptates pariatur qui sunt aut. Quaerat possimus aut ratione. Vel qui dicta explicabo incidunt et.', '\"https:\\/\\/via.placeholder.com\\/640x480.png\\/00eeaa?text=ut\"', '2024-12-28 18:07:07', '2024-12-28 18:07:07'),
(78, 'Prof.', 'Consequatur quia molestiae sit voluptatem. Molestiae sequi adipisci tempore recusandae ad est sequi consequatur. Corrupti vitae necessitatibus consequatur molestias odit adipisci.', '\"https:\\/\\/via.placeholder.com\\/640x480.png\\/0000bb?text=laudantium\"', '2024-12-28 18:07:07', '2024-12-28 18:07:07'),
(79, 'Mr.', 'Amet dolorem voluptas illum ad dolor commodi vel. Nihil quia maxime nobis iure quia. Ab eius deserunt et quasi quisquam.', '\"https:\\/\\/via.placeholder.com\\/640x480.png\\/00ee33?text=ea\"', '2024-12-28 18:07:07', '2024-12-28 18:07:07'),
(80, 'Mrs.', 'Aut asperiores qui nihil dolorem incidunt. Impedit ullam nihil omnis debitis repellat impedit inventore. Qui quibusdam ut et et est nisi non.', '\"https:\\/\\/via.placeholder.com\\/640x480.png\\/004477?text=molestias\"', '2024-12-28 18:07:07', '2024-12-28 18:07:07'),
(81, 'Dr.', 'Sed magni odit quisquam qui vero. Labore omnis magnam quis quasi sed. Ab et qui libero illum.', '\"https:\\/\\/via.placeholder.com\\/640x480.png\\/00bb00?text=odio\"', '2024-12-28 18:07:07', '2024-12-28 18:07:07'),
(82, 'Mrs.', 'Et voluptatum molestiae quod. Tempora voluptatum ratione vero ipsum sint. Velit recusandae aperiam impedit libero et sed aut. Maiores voluptatibus deserunt est rerum et.', '\"https:\\/\\/via.placeholder.com\\/640x480.png\\/0022bb?text=reiciendis\"', '2024-12-28 18:07:07', '2024-12-28 18:07:07'),
(83, 'Prof.', 'Est impedit et tempora laborum dolor. Et asperiores non est voluptas id eveniet veritatis odit. Rerum est sit amet molestiae ipsa cum. Quibusdam suscipit ut est minus.', '\"https:\\/\\/via.placeholder.com\\/640x480.png\\/00ccaa?text=nemo\"', '2024-12-28 18:07:07', '2024-12-28 18:07:07'),
(84, 'Dr.', 'Qui eos animi eaque eius et. Nam iste illo quibusdam vitae eveniet. Beatae corporis fuga velit adipisci omnis libero cupiditate.', '\"https:\\/\\/via.placeholder.com\\/640x480.png\\/002299?text=consequatur\"', '2024-12-28 18:07:07', '2024-12-28 18:07:07'),
(85, 'Miss', 'Sapiente mollitia unde fugit architecto velit qui magni. Sit repellat praesentium debitis ipsum dicta vitae nihil.', '\"https:\\/\\/via.placeholder.com\\/640x480.png\\/0066ff?text=voluptatem\"', '2024-12-28 18:07:07', '2024-12-28 18:07:07'),
(86, 'Dr.', 'Ex quasi libero nihil inventore harum nemo aliquam quia. Eligendi id nemo eaque voluptates iure. Provident suscipit laborum eum accusamus temporibus.', '\"https:\\/\\/via.placeholder.com\\/640x480.png\\/0044cc?text=vel\"', '2024-12-28 18:07:07', '2024-12-28 18:07:07'),
(87, 'Ms.', 'Delectus dolorem expedita quam veniam inventore quia at. Qui autem voluptatem magni natus itaque repudiandae sed. Neque et et perspiciatis.', '\"https:\\/\\/via.placeholder.com\\/640x480.png\\/006677?text=fugit\"', '2024-12-28 18:07:07', '2024-12-28 18:07:07'),
(88, 'Miss', 'Dignissimos libero odio culpa architecto. Animi sequi sunt quis ducimus vel et. Culpa vitae molestiae ducimus qui. Et nisi non adipisci impedit.', '\"https:\\/\\/via.placeholder.com\\/640x480.png\\/007788?text=voluptas\"', '2024-12-28 18:07:07', '2024-12-28 18:07:07'),
(89, 'Miss', 'Assumenda deserunt quidem est eligendi consequatur. Iste non ad veniam sed. Et et consequatur dolorum atque dolorum omnis eos.', '\"https:\\/\\/via.placeholder.com\\/640x480.png\\/00bb77?text=aliquid\"', '2024-12-28 18:07:07', '2024-12-28 18:07:07'),
(90, 'Mr.', 'Aut nihil dolores est nisi. Autem porro veniam omnis provident sequi modi. Omnis repellendus debitis quam et et saepe. Qui quam magnam dolores dolorem qui.', '\"https:\\/\\/via.placeholder.com\\/640x480.png\\/009988?text=odit\"', '2024-12-28 18:07:07', '2024-12-28 18:07:07'),
(91, 'Miss', 'Ut veniam est expedita qui quis blanditiis rem est. Qui magni vel molestiae laboriosam quod. Fuga consequatur veniam quia ducimus voluptatum. Nam consequatur aut magni porro et.', '\"https:\\/\\/via.placeholder.com\\/640x480.png\\/009977?text=velit\"', '2024-12-28 18:07:07', '2024-12-28 18:07:07'),
(92, 'Prof.', 'Sit voluptates inventore eveniet magni iusto delectus aliquid vel. Laboriosam laboriosam explicabo eos magni et enim quam. Consequatur est non quas adipisci quibusdam.', '\"https:\\/\\/via.placeholder.com\\/640x480.png\\/000088?text=amet\"', '2024-12-28 18:07:07', '2024-12-28 18:07:07'),
(93, 'Mrs.', 'Culpa numquam ea vel consequuntur ab vitae rerum. Voluptas et numquam sit accusamus. Fuga voluptatem corporis iure eos excepturi vitae nostrum. Quia a voluptatem quis iusto soluta illum eum tenetur.', '\"https:\\/\\/via.placeholder.com\\/640x480.png\\/00ccaa?text=sunt\"', '2024-12-28 18:07:07', '2024-12-28 18:07:07'),
(94, 'Dr.', 'Tenetur ut sed aut unde mollitia ipsam veniam et. Rerum omnis rerum accusamus impedit molestias. Rerum amet enim et earum laboriosam vero facilis. Aut sed nobis rerum exercitationem culpa et est.', '\"https:\\/\\/via.placeholder.com\\/640x480.png\\/0088dd?text=eaque\"', '2024-12-28 18:07:07', '2024-12-28 18:07:07'),
(95, 'Dr.', 'Facere eum blanditiis aut sit id. Voluptas excepturi expedita iste minima possimus commodi officiis. Minima iste voluptate hic nihil.', '\"https:\\/\\/via.placeholder.com\\/640x480.png\\/00cc44?text=odit\"', '2024-12-28 18:07:07', '2024-12-28 18:07:07'),
(96, 'Mrs.', 'Laborum est sunt esse et sit officiis voluptatem. Deleniti eos ducimus aut accusantium omnis nemo sit. Dolorem illum nihil ullam aperiam minus pariatur. Impedit soluta saepe aut eius cumque.', '\"https:\\/\\/via.placeholder.com\\/640x480.png\\/00bb33?text=tenetur\"', '2024-12-28 18:07:07', '2024-12-28 18:07:07'),
(97, 'Mrs.', 'Exercitationem laboriosam placeat vel illum. Laudantium vitae consequatur voluptatibus.', '\"https:\\/\\/via.placeholder.com\\/640x480.png\\/001199?text=vel\"', '2024-12-28 18:07:07', '2024-12-28 18:07:07'),
(98, 'Mrs.', 'Dolore tenetur ea eos non quo ut accusamus. Et labore ut enim aperiam et. Dolorum quas recusandae consequatur quam illo aperiam eos reprehenderit. Autem qui molestiae eos.', '\"https:\\/\\/via.placeholder.com\\/640x480.png\\/007777?text=aliquam\"', '2024-12-28 18:07:07', '2024-12-28 18:07:07'),
(99, 'Prof.', 'Cupiditate vel in corrupti dolores. Ullam pariatur ut et laboriosam laborum quia expedita.', '\"https:\\/\\/via.placeholder.com\\/640x480.png\\/0088aa?text=in\"', '2024-12-28 18:07:07', '2024-12-28 18:07:07'),
(100, 'Miss', 'Quibusdam aut commodi et perferendis. Id illum eos possimus iusto inventore quisquam vitae vel.', '\"https:\\/\\/via.placeholder.com\\/640x480.png\\/00cc44?text=quo\"', '2024-12-28 18:07:07', '2024-12-28 18:07:07'),
(101, 'Winter Wardrobe Essentials for a Cozy Yet Chic Look', 'Dive into the latest fashion trends taking the world by storm. From bold colors to retro-inspired pieces', '\"http:\\/\\/10.0.80.16:8000\\/storage\\/blog_images\\/bjxkZ3O0c2M4GmAvrfEiUi48RyqDpG8YiGADfceO.png\"', '2024-12-30 14:27:31', '2024-12-30 14:27:31'),
(102, 'Winter Wardrobe Essentials for a Cozy Yet Chic Look', 'Dive into the latest fashion trends taking the world by storm. From bold colors to retro-inspired pieces', '\"http:\\/\\/10.0.80.16:8000\\/storage\\/blog_images\\/gtkcpHu5RjPVJ8DXoMOhyUr7sLvEONJcBSSiU0Sn.jpg\"', '2024-12-30 14:27:50', '2024-12-30 14:27:50');

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `f_a_q_s`
--

CREATE TABLE `f_a_q_s` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `question` varchar(255) NOT NULL,
  `answer` longtext NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `f_a_q_s`
--

INSERT INTO `f_a_q_s` (`id`, `question`, `answer`, `created_at`, `updated_at`) VALUES
(1, 'Lorem ipsum dolor sit amet consectetur?', 'Lorem ipsum dolor sit amet consectetur. Vivamus adipiscing scelerisque lacus accumsan nunc feugiat pharetra pellentesque. Lobortis quis ultrices amet viverra sem et. Nisl vitae pharetra nulla praesent rhoncus. Amet hac dui semper nulla nec mi tincidunt. Habitant et faucibus quisque adipiscing eu. Ipsum donec porta a rhoncus adipiscing nunc faucibus. Mauris semper non vulputate nunc arcu ut. Lorem tristique varius tellus egestas risus risus. Ultricies elementum morbi amet egestas ridiculus.', '2024-12-29 09:39:00', '2024-12-29 09:39:00');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`id`, `queue`, `payload`, `attempts`, `reserved_at`, `available_at`, `created_at`) VALUES
(1, 'default', '{\"uuid\":\"87481f3e-2585-41e0-93cf-a04f0da6b6ad\",\"displayName\":\"App\\\\Mail\\\\ProductAddedMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":15:{s:8:\\\"mailable\\\";O:25:\\\"App\\\\Mail\\\\ProductAddedMail\\\":3:{s:7:\\\"product\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:18:\\\"App\\\\Models\\\\Product\\\";s:2:\\\"id\\\";i:1;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:14:\\\"user@gmail.com\\\";}}s:6:\\\"mailer\\\";s:4:\\\"smtp\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\"}}', 0, NULL, 1735385965, 1735385965),
(2, 'default', '{\"uuid\":\"3bc14a04-bc06-4388-acbc-685b15c01a7c\",\"displayName\":\"App\\\\Mail\\\\ProductAddedMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":15:{s:8:\\\"mailable\\\";O:25:\\\"App\\\\Mail\\\\ProductAddedMail\\\":3:{s:7:\\\"product\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:18:\\\"App\\\\Models\\\\Product\\\";s:2:\\\"id\\\";i:1;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:14:\\\"user@gmail.com\\\";}}s:6:\\\"mailer\\\";s:4:\\\"smtp\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\"}}', 0, NULL, 1735387948, 1735387948),
(3, 'default', '{\"uuid\":\"39c7914e-84ca-414f-bbbd-4f8aceea1cb4\",\"displayName\":\"App\\\\Mail\\\\ProductAddedMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":15:{s:8:\\\"mailable\\\";O:25:\\\"App\\\\Mail\\\\ProductAddedMail\\\":3:{s:7:\\\"product\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:18:\\\"App\\\\Models\\\\Product\\\";s:2:\\\"id\\\";i:1;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:23:\\\"capob71571@matmayer.com\\\";}}s:6:\\\"mailer\\\";s:4:\\\"smtp\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\"}}', 0, NULL, 1735387948, 1735387948),
(4, 'default', '{\"uuid\":\"5bab4566-65ff-4a47-8778-e77e43a2527a\",\"displayName\":\"App\\\\Mail\\\\ProductAddedMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":15:{s:8:\\\"mailable\\\";O:25:\\\"App\\\\Mail\\\\ProductAddedMail\\\":3:{s:7:\\\"product\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:18:\\\"App\\\\Models\\\\Product\\\";s:2:\\\"id\\\";i:2;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:14:\\\"user@gmail.com\\\";}}s:6:\\\"mailer\\\";s:4:\\\"smtp\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\"}}', 0, NULL, 1735447468, 1735447468),
(5, 'default', '{\"uuid\":\"16024ed7-ce99-44c2-bb8c-7d1fc5010158\",\"displayName\":\"App\\\\Mail\\\\ProductAddedMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":15:{s:8:\\\"mailable\\\";O:25:\\\"App\\\\Mail\\\\ProductAddedMail\\\":3:{s:7:\\\"product\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:18:\\\"App\\\\Models\\\\Product\\\";s:2:\\\"id\\\";i:2;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:23:\\\"capob71571@matmayer.com\\\";}}s:6:\\\"mailer\\\";s:4:\\\"smtp\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:3:\\\"job\\\";N;}\"}}', 0, NULL, 1735447468, 1735447468);

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2024_12_21_055928_create_personal_access_tokens_table', 1),
(5, '2024_12_21_082038_create_categories_table', 1),
(6, '2024_12_21_082340_create_products_table', 1),
(7, '2024_12_21_082938_create_blogs_table', 1),
(8, '2024_12_21_082957_create_abouts_table', 1),
(9, '2024_12_21_111600_create_notifications_table', 1),
(10, '2024_12_22_051011_create_brands_table', 1),
(11, '2024_12_22_083133_create_f_a_q_s_table', 1),
(13, '2024_12_24_055718_create_reviews_table', 1),
(14, '2024_12_22_101232_create_orders_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` char(36) NOT NULL,
  `type` varchar(255) NOT NULL,
  `notifiable_type` varchar(255) NOT NULL,
  `notifiable_id` bigint(20) UNSIGNED NOT NULL,
  `data` text NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `type`, `notifiable_type`, `notifiable_id`, `data`, `read_at`, `created_at`, `updated_at`) VALUES
('01235bdf-c443-4738-a70d-9799004d1bc3', 'App\\Notifications\\OrderPlaced', 'App\\Models\\User', 1, '{\"order_id\":2,\"amount\":\"100\",\"status\":null}', '2024-12-30 14:39:01', '2024-12-28 20:14:30', '2024-12-30 14:39:01'),
('03c0c86f-9c0d-4c8d-9d88-098194580e2e', 'App\\Notifications\\OrderPlaced', 'App\\Models\\User', 1, '{\"order_id\":4,\"amount\":\"2090.66\",\"status\":null}', '2024-12-30 14:39:01', '2024-12-28 20:15:02', '2024-12-30 14:39:01'),
('049197f5-bcd2-4881-b854-fa59c86574a5', 'App\\Notifications\\OrderPlaced', 'App\\Models\\User', 1, '{\"order_id\":19,\"product_title\":\"Mobile\",\"order_date\":\"2024-12-29 05:39:23\",\"quantity\":\"5\",\"address\":\"1234 Elm St., Springfield, \",\"amount\":\"100\",\"status\":\"delivered\"}', '2024-12-30 14:39:01', '2024-12-29 11:39:23', '2024-12-30 14:39:01'),
('0bae2a83-2f80-4b33-b15f-a5dd430cfe3b', 'App\\Notifications\\OrderPlaced', 'App\\Models\\User', 1, '{\"order_id\":5,\"amount\":\"100\",\"status\":null}', '2024-12-30 14:39:01', '2024-12-28 20:15:38', '2024-12-30 14:39:01'),
('0fcccc4e-59dd-459e-9487-e26d8950b226', 'App\\Notifications\\OrderPlaced', 'App\\Models\\User', 1, '{\"order_id\":8,\"amount\":\"2090.66\",\"status\":\"delivered\"}', '2024-12-30 14:39:01', '2024-12-28 20:19:46', '2024-12-30 14:39:01'),
('1da46ee4-6642-4065-9a0b-d5cb0fe537f0', 'App\\Notifications\\OrderPlaced', 'App\\Models\\User', 1, '{\"order_id\":13,\"amount\":\"2090.66\",\"status\":\"delivered\"}', '2024-12-30 14:39:01', '2024-12-29 10:36:56', '2024-12-30 14:39:01'),
('24aafbe6-eeec-4846-b655-7daa95c787fb', 'App\\Notifications\\OrderPlaced', 'App\\Models\\User', 1, '{\"order_id\":15,\"amount\":\"95.00\",\"status\":\"delivered\"}', '2024-12-30 14:39:01', '2024-12-29 10:46:35', '2024-12-30 14:39:01'),
('295d8241-6e9f-4025-b5bb-f196d4531e86', 'App\\Notifications\\OrderPlaced', 'App\\Models\\User', 1, '{\"order_id\":9,\"amount\":\"100\",\"status\":\"delivered\"}', '2024-12-30 14:39:01', '2024-12-28 20:20:51', '2024-12-30 14:39:01'),
('2e2b0ade-cf14-46d0-98e7-44105a5b2108', 'App\\Notifications\\ProductAddedNotification', 'App\\Models\\User', 26, '{\"title\":\"Quo molestiae sed ut of yellow product\",\"description\":\"Competently productivate open-source results through enterprise interfaces. Dynamically envisioneer cutting-edge scenarios vis-a-vis front-end value. Proactively embrace prospective methodologies vis-a-vis leveraged innovation. Enthusiastically communicate global models.\",\"price\":\"95\"}', NULL, '2024-12-29 10:44:28', '2024-12-29 10:44:28'),
('31dfa082-9eab-4b3e-9c73-b42bbe64c89e', 'App\\Notifications\\OrderPlaced', 'App\\Models\\User', 1, '{\"order_id\":21,\"product_title\":\"Mobile\",\"order_date\":\"2024-12-29 05:52:53\",\"quantity\":3,\"address\":\"1234 Elm St., Springfield, \",\"amount\":\"100\",\"status\":\"delivered\"}', '2024-12-30 14:17:54', '2024-12-29 11:52:53', '2024-12-30 14:17:54'),
('3879e75c-f94f-4a6c-a4c4-79db46f0f6e7', 'App\\Notifications\\ProductAddedNotification', 'App\\Models\\User', 2, '{\"title\":\"Quo molestiae sed ut of yellow product\",\"description\":\"Competently productivate open-source results through enterprise interfaces. Dynamically envisioneer cutting-edge scenarios vis-a-vis front-end value. Proactively embrace prospective methodologies vis-a-vis leveraged innovation. Enthusiastically communicate global models.\",\"price\":\"95\"}', NULL, '2024-12-29 10:44:28', '2024-12-29 10:44:28'),
('40669ff9-70d9-4f3f-928a-786c8d2b4c3e', 'App\\Notifications\\OrderPlaced', 'App\\Models\\User', 1, '{\"order_id\":20,\"product_title\":\"Mobile\",\"order_date\":\"2024-12-29 05:44:21\",\"quantity\":2,\"address\":\"1234 Elm St., Springfield, \",\"amount\":\"100\",\"status\":\"delivered\"}', '2024-12-30 14:18:22', '2024-12-29 11:44:21', '2024-12-30 14:18:22'),
('44193135-ca27-43bc-8b62-f534425797cf', 'App\\Notifications\\ProductAddedNotification', 'App\\Models\\User', 27, '{\"title\":\"Quo molestiae sed ut of yellow product\",\"description\":\"Competently productivate open-source results through enterprise interfaces. Dynamically envisioneer cutting-edge scenarios vis-a-vis front-end value. Proactively embrace prospective methodologies vis-a-vis leveraged innovation. Enthusiastically communicate global models.\",\"price\":\"95\"}', NULL, '2024-12-29 10:44:28', '2024-12-29 10:44:28'),
('4d98f67b-8b10-49ae-9f74-6676731a6802', 'App\\Notifications\\OrderPlaced', 'App\\Models\\User', 1, '{\"order_id\":12,\"amount\":\"2090.66\",\"status\":\"delivered\"}', '2024-12-29 09:37:10', '2024-12-28 20:22:47', '2024-12-29 09:37:10'),
('62eb4ce0-6244-4b82-9eb3-9d9ddd7e329c', 'App\\Notifications\\OrderPlaced', 'App\\Models\\User', 1, '{\"order_id\":6,\"amount\":\"100\",\"status\":\"delivered\"}', '2024-12-30 14:39:01', '2024-12-28 20:18:17', '2024-12-30 14:39:01'),
('65504801-1db3-44ad-b3b1-5471af1e65e0', 'App\\Notifications\\OrderPlaced', 'App\\Models\\User', 1, '{\"order_id\":3,\"amount\":\"100\",\"status\":null}', '2024-12-30 14:39:01', '2024-12-28 20:14:38', '2024-12-30 14:39:01'),
('69df6c0f-fecc-4bcc-a783-8c21b171f2a8', 'App\\Notifications\\OrderPlaced', 'App\\Models\\User', 1, '{\"order_id\":10,\"amount\":\"2090.66\",\"status\":\"delivered\"}', NULL, '2024-12-28 20:21:01', '2024-12-30 14:39:01'),
('723abba7-8dea-4fe4-a14c-b5fb42574050', 'App\\Notifications\\OrderPlaced', 'App\\Models\\User', 1, '{\"order_id\":1,\"amount\":\"100\",\"status\":null}', '2024-12-30 14:39:01', '2024-12-28 20:14:27', '2024-12-30 14:39:01'),
('7a3cd715-4686-475c-970e-8734de7f92da', 'App\\Notifications\\OrderPlaced', 'App\\Models\\User', 1, '{\"order_id\":22,\"product_title\":\"Quo molestiae sed ut of yellow product\",\"order_date\":\"2024-12-29 05:53:35\",\"quantity\":4,\"address\":\"1234 Elm St., Springfield, \",\"amount\":\"100\",\"status\":\"delivered\"}', '2024-12-30 14:17:21', '2024-12-29 11:53:35', '2024-12-30 14:17:21'),
('7bc4a77b-e341-4210-82c6-aafc29849d84', 'App\\Notifications\\ProductAddedNotification', 'App\\Models\\User', 2, '{\"title\":\"Mobile\",\"description\":\"This is a test product\",\"price\":\"2090.66\"}', NULL, '2024-12-28 17:39:25', '2024-12-28 17:39:25'),
('81f97d9a-ed66-4b0a-b008-53e0b7ea5f62', 'App\\Notifications\\OrderPlaced', 'App\\Models\\User', 1, '{\"order_id\":17,\"amount\":\"100\",\"status\":\"delivered\"}', '2024-12-30 14:39:01', '2024-12-29 11:04:32', '2024-12-30 14:39:01'),
('840b3619-15bc-4388-a53a-301de7bfdf30', 'App\\Notifications\\OrderPlaced', 'App\\Models\\User', 1, '{\"order_id\":16,\"amount\":\"95.00\",\"status\":\"delivered\"}', '2024-12-30 14:39:01', '2024-12-29 11:04:12', '2024-12-30 14:39:01'),
('84897ee2-d460-4062-ab81-937288ae94ee', 'App\\Notifications\\OrderPlaced', 'App\\Models\\User', 1, '{\"order_id\":18,\"amount\":\"100\",\"status\":\"delivered\"}', '2024-12-30 14:39:01', '2024-12-29 11:11:36', '2024-12-30 14:39:01'),
('88a1eea9-a66b-44a0-8a68-908cc236da1d', 'App\\Notifications\\OrderPlaced', 'App\\Models\\User', 1, '{\"order_id\":7,\"amount\":\"100\",\"status\":\"delivered\"}', '2024-12-30 14:39:01', '2024-12-28 20:18:41', '2024-12-30 14:39:01'),
('8d44a7c0-a79b-4789-b21e-da094ad0de1c', 'App\\Notifications\\OrderPlaced', 'App\\Models\\User', 1, '{\"order_id\":24,\"product_title\":\"Quo molestiae sed ut of yellow product\",\"order_date\":\"2024-12-29 06:06:20\",\"quantity\":1,\"address\":\"1234 Elm St., Springfield, \",\"amount\":\"100\",\"status\":\"delivered\"}', '2024-12-30 12:55:53', '2024-12-29 12:06:20', '2024-12-30 12:55:53'),
('933a9379-f804-4422-b216-f25d9b44c674', 'App\\Notifications\\OrderPlaced', 'App\\Models\\User', 1, '{\"order_id\":11,\"amount\":\"2090.66\",\"status\":\"delivered\"}', '2024-12-30 14:39:01', '2024-12-28 20:21:24', '2024-12-30 14:39:01'),
('9cfaf180-2a93-455a-ae54-7af3f65e16cb', 'App\\Notifications\\ProductAddedNotification', 'App\\Models\\User', 1, '{\"title\":\"Quo molestiae sed ut of yellow product\",\"description\":\"Competently productivate open-source results through enterprise interfaces. Dynamically envisioneer cutting-edge scenarios vis-a-vis front-end value. Proactively embrace prospective methodologies vis-a-vis leveraged innovation. Enthusiastically communicate global models.\",\"price\":\"95\"}', '2024-12-30 14:39:01', '2024-12-29 10:44:28', '2024-12-30 14:39:01'),
('a3e8dba8-fda2-4872-a11e-297be97225c0', 'App\\Notifications\\ProductAddedNotification', 'App\\Models\\User', 3, '{\"title\":\"Quo molestiae sed ut of yellow product\",\"description\":\"Competently productivate open-source results through enterprise interfaces. Dynamically envisioneer cutting-edge scenarios vis-a-vis front-end value. Proactively embrace prospective methodologies vis-a-vis leveraged innovation. Enthusiastically communicate global models.\",\"price\":\"95\"}', NULL, '2024-12-29 10:44:28', '2024-12-29 10:44:28'),
('aa702667-39a2-4ead-b81b-355b1fba3423', 'App\\Notifications\\ProductAddedNotification', 'App\\Models\\User', 1, '{\"title\":\"Mobile\",\"description\":\"This is a test product\",\"price\":\"2090.66\"}', '2024-12-30 14:39:01', '2024-12-28 17:39:25', '2024-12-30 14:39:01'),
('bae74157-6ce5-4289-9c3f-4c1133158a80', 'App\\Notifications\\OrderPlaced', 'App\\Models\\User', 1, '{\"order_id\":14,\"amount\":\"100\",\"status\":\"delivered\"}', '2024-12-30 14:39:01', '2024-12-29 10:37:33', '2024-12-30 14:39:01'),
('e396f4ee-256d-48da-8c3c-8004a37a1862', 'App\\Notifications\\OrderPlaced', 'App\\Models\\User', 1, '{\"order_id\":23,\"product_title\":\"Mobile\",\"order_date\":\"2024-12-29 06:05:39\",\"quantity\":1,\"address\":\"1234 Elm St., Springfield, \",\"amount\":\"100\",\"status\":\"delivered\"}', '2024-12-30 14:15:21', '2024-12-29 12:05:39', '2024-12-30 14:15:21');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `transaction_id` varchar(255) DEFAULT NULL,
  `amount` decimal(8,2) NOT NULL,
  `status` enum('delivered','pending') NOT NULL DEFAULT 'pending',
  `street_address` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `contact` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `product_id`, `transaction_id`, `amount`, `status`, `street_address`, `city`, `contact`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '\'asdfasdfasdfsadf', 100.00, 'pending', '1234 Elm St.', 'Springfield', '123-456-7890', '2024-12-19 20:14:26', '2024-12-19 20:14:26'),
(2, 1, 1, 'abc123456', 100.00, 'pending', 'IL', 'Springfield', '123-456-7890', '2024-02-15 20:14:30', '2024-02-18 20:14:30'),
(3, 1, 1, '\'asdfasdfasdfsadf', 500.00, 'pending', '1234 Elm St.', 'Springfield', '123-456-7890', '2024-02-14 20:14:38', '2024-02-16 20:14:38'),
(4, 3, 1, 'pi_3Qb0nAK0hXSiOrOR1FfdGlSg', 200.00, 'pending', 'Ratione quia volupta', 'Totam irure qui aliq', NULL, '2024-03-05 20:15:02', '2024-03-08 20:15:02'),
(5, 1, 1, '\'asdfasdfasdfsadf', 100.00, 'pending', '1234 Elm St.', 'Springfield', '123-456-7890', '2024-03-13 20:15:38', '2024-03-15 20:15:38'),
(6, 1, 1, '\'asdfasdfasdfsadf', 100.00, 'delivered', '1234 Elm St.', 'Springfield', '123-456-7890', '2024-04-09 20:18:17', '2024-04-11 20:18:17'),
(7, 1, 2, '\'asdfasdfasdfsadf', 100.00, 'delivered', '1234 Elm St.', 'Springfield', '123-456-7890', '2024-05-16 20:18:41', '2024-05-23 20:18:41'),
(8, 3, 2, 'pi_3Qb0rlK0hXSiOrOR132KCTFg', 200.00, 'delivered', 'Ab dicta excepteur a', 'Et sint proident cu', NULL, '2024-03-13 20:19:46', '2024-03-22 20:19:46'),
(9, 1, 1, '\'asdfasdfasdfsadf', 100.00, 'delivered', '1234 Elm St.', 'Springfield', '123-456-7890', '2024-11-04 20:20:51', '2024-11-08 20:20:51'),
(10, 3, 1, 'pi_3Qb0syK0hXSiOrOR1qvVfmvo', 400.00, 'delivered', 'Ab dicta excepteur a', 'Et sint proident cu', NULL, '2024-08-22 20:21:01', '2024-08-30 20:21:01'),
(11, 3, 1, 'pi_3Qb0tLK0hXSiOrOR0J7HOtpM', 2090.66, 'delivered', 'Ab dicta excepteur a', 'Et sint proident cu', '123-456-7890', '2024-12-29 20:21:24', '2024-12-30 20:21:24'),
(12, 3, 1, 'pi_3Qb0uTK0hXSiOrOR1mBROCy0', 2090.66, 'delivered', 'Ab dicta excepteur a', 'Et sint proident cu', '123-456-7890', '2024-06-12 20:22:46', '2024-06-19 20:22:46'),
(13, 2, 1, 'pi_3QbEFCK0hXSiOrOR1ZCXuO1K', 2090.66, 'delivered', 'Voluptatum nisi dict', 'Quae ad rerum qui ad', '903', '2023-12-15 10:36:55', '2023-12-30 10:36:55'),
(14, 2, 1, '\'asdfasdfasdfsadf', 100.00, 'delivered', '1234 Elm St.', 'Springfield', '123-456-7890', '2024-07-18 10:37:33', '2024-07-18 10:37:33'),
(15, 2, 2, 'pi_3QbEOcK0hXSiOrOR03o8i1jR', 95.00, 'delivered', 'Fulbaria', 'Fulbaria', '01860650703', '2024-12-29 10:46:35', '2024-12-29 10:46:35'),
(17, 2, 2, '\'asdfasdfasdfsadf', 100.00, 'delivered', '1234 Elm St.', 'Springfield', '123-456-7890', '2024-08-13 11:04:32', '2024-08-22 11:04:32'),
(19, 2, 1, '\'asdfasdfasdfsadf', 300.00, 'delivered', '1234 Elm St.', 'Springfield', '123-456-7890', '2023-12-13 11:39:23', '2023-12-12 11:39:23'),
(20, 2, 1, '\'asdfasdfasdfsadf', 100.00, 'delivered', '1234 Elm St.', 'Springfield', '123-456-7890', '2024-01-02 11:44:21', '2024-01-10 11:44:21'),
(21, 2, 1, '\'asdfasdfasdfsadf', 100.00, 'delivered', '1234 Elm St.', 'Springfield', '123-456-7890', '2024-10-09 11:52:53', '2024-10-15 11:52:53'),
(22, 2, 2, '\'asdfasdfasdfsadf', 3200.00, 'delivered', '1234 Elm St.', 'Springfield', '123-456-7890', '2023-11-14 11:53:35', '2023-11-23 11:53:35'),
(23, 2, 1, '\'asdfasdfasdfsadf', 60.00, 'delivered', '1234 Elm St.', 'Springfield', '123-456-7890', '2024-12-29 12:05:39', '2024-12-29 12:05:39'),
(26, 2, 1, '\'asdfasdfasdfsadf', 100.00, 'delivered', '1234 Elm St.', 'Springfield', '123-456-7890', '2024-12-29 13:06:42', '2024-12-29 13:06:42'),
(28, 2, 1, 'fhfgh', 3456.00, 'delivered', 'fgfdh', 'gsd', '3543536', '2024-12-29 10:46:03', '2024-12-29 10:46:03');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `category` varchar(255) DEFAULT NULL,
  `brand` varchar(255) DEFAULT NULL,
  `image` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`image`)),
  `price` decimal(8,2) NOT NULL,
  `sale_price` decimal(8,2) NOT NULL,
  `quantity` varchar(255) NOT NULL,
  `SKU` varchar(255) DEFAULT NULL,
  `stock` varchar(255) DEFAULT NULL,
  `tags` varchar(255) DEFAULT NULL,
  `color` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`color`)),
  `size` varchar(255) DEFAULT NULL,
  `description` longtext NOT NULL,
  `no_of_sale` bigint(20) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'count total no of sale of this product',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `title`, `category`, `brand`, `image`, `price`, `sale_price`, `quantity`, `SKU`, `stock`, `tags`, `color`, `size`, `description`, `no_of_sale`, `created_at`, `updated_at`) VALUES
(1, 'Mobile', NULL, NULL, '[\"http:\\/\\/10.0.80.16:8000\\/storage\\/product_images\\/S1FgROS6wickRATB4sO7cRfwWaezcgbqYhld8Wjj.png\",\"http:\\/\\/10.0.80.16:8000\\/storage\\/product_images\\/Cs6OYrQfiGwZPPEcj5blYKmTDTQ3oovCZpEvJBks.png\",\"http:\\/\\/10.0.80.16:8000\\/storage\\/product_images\\/YrUOpx1pU5lxaitvxDUvAQ7HMQoYfXJh4IE9d9MG.png\"]', 2090.66, 3450.00, '1', NULL, 'In Stock', NULL, NULL, NULL, 'This is a test products', 1, '2024-12-28 17:39:23', '2024-12-29 13:06:42'),
(2, 'Quo molestiae sed ut of yellow product', NULL, NULL, '[\"http:\\/\\/10.0.80.16:8000\\/storage\\/product_images\\/3vM1Y8Hzq4IoXpuG4cNH6WxoLGW2eVeLHcddNaGj.png\",\"http:\\/\\/10.0.80.16:8000\\/storage\\/product_images\\/Hf7ewSxKw9jMGAPSgHYKIzqNUPIbbaYoD5wwwCeV.png\",\"http:\\/\\/10.0.80.16:8000\\/storage\\/product_images\\/ddYvrLxjOXFN4GTECUjcxw4m3HuM68k9ECl1xKZO.png\"]', 95.00, 28.00, '36', NULL, 'In Stock', NULL, NULL, NULL, 'Competently productivate open-source results through enterprise interfaces. Dynamically envisioneer cutting-edge scenarios vis-a-vis front-end value. Proactively embrace prospective methodologies vis-a-vis leveraged innovation. Enthusiastically communicate global models.', 1, '2024-12-29 10:44:27', '2024-12-29 13:06:21');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `rating` int(11) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `user_id`, `product_id`, `rating`, `comment`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 5, 'Credibly scale tactical potentialities with professional methods of empowerment. Objectively incubate effective meta-services and 24/7 infomediaries. Appropriately redefine cross-unit content with revolutionary leadership. Dynamically enable.', '2024-12-29 10:38:39', '2024-12-29 10:38:39'),
(2, 2, 2, 4, 'Distinctively expedite interoperable networks for out-of-the-box e-commerce. Intrinsicly evolve real-time communities with holistic value. Distinctively cultivate prospective manufactured products without standardized information. Compellingly maximize fully.', '2024-12-29 10:47:09', '2024-12-29 10:47:09');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role` enum('ADMIN','USER') NOT NULL DEFAULT 'USER',
  `password` varchar(255) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `contact` varchar(255) DEFAULT NULL,
  `image` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`image`)),
  `otp` varchar(255) DEFAULT NULL,
  `otp_expires_at` varchar(255) DEFAULT NULL,
  `status` enum('inactive','active') NOT NULL DEFAULT 'inactive',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `role`, `password`, `address`, `contact`, `image`, `otp`, `otp_expires_at`, `status`, `email_verified_at`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'mehedi', 'admin@gmail.com', 'ADMIN', '$2y$12$rsgjMdmNU8VDRv5zQ4BUbuo35yDBW3hCmUuZR8p1sJ7s7QTXoprUi', 'Ratione quis fugiat', '513546546', '\"http:\\/\\/10.0.80.16:8000\\/storage\\/profile_images\\/e7mM5g0gcIYLkXJoMQfh8zdZDqUJgX4ZR36SxJRA.png\"', NULL, NULL, 'active', '2024-06-12 17:39:02', NULL, '2024-02-06 17:39:02', '2024-12-30 15:00:08'),
(2, 'Jane Valdez', 'user@gmail.com', 'USER', '$2y$12$3QCSkqIPHPE/2RsIoGWEMOoDAqdg8Q7TEZPBbFvKRqr9ByDZ8ve2e', NULL, NULL, '\"http:\\/\\/10.0.80.16:8000\\/storage\\/profile_images\\/7W6ljQNtPol9MJMzipzqBgXlqoI9z9ZC4j4i8R1L.png\"', NULL, NULL, 'active', '2024-10-14 17:39:01', NULL, '2024-03-04 17:39:01', '2024-12-30 11:33:49'),
(3, 'Mehedi hasan', 'capob71571@matmayer.com', 'USER', '$2y$12$hdbPA8aZ4PrF27eRGJHBh.0qMGixEPaD4AD2B2dRAoPjeMw9OXY7.', NULL, NULL, '\"http:\\/\\/10.0.80.16:8000\\/storage\\/profile_images\\/YRHgdgm5vH1xEqICuPjubnEatU8XvaJyuMi0qv96.png\"', NULL, '2024-12-28 12:08:13', 'active', '2024-10-14 17:58:45', NULL, '2024-04-02 17:58:14', '2024-04-08 18:18:05'),
(26, 'Guy Rath', 'Rebecca.Skiles@gmail.com', 'USER', '$2y$12$Y/VjK7OVM44FTUbJNPMOHeDZ6/vDgkjxKEg1Hc/CWG6n08PbxvBrC', NULL, NULL, NULL, '5953', '2024-12-29 04:23:15', 'active', '2024-09-09 06:43:39', NULL, '2024-04-09 10:13:15', '2024-04-16 10:13:15'),
(27, 'Zenia Hahn', 'bigabygufa@mailinator.com', 'USER', '$2y$12$5SrDI7Z0Hkfis7fhcljxAOSJDYtUl2pwiVf.tCL.59Or08RmifUvG', NULL, NULL, NULL, '9153', '2024-12-29 04:24:53', 'active', '2024-12-10 06:44:32', NULL, '2024-05-07 10:14:53', '2024-05-14 10:14:53'),
(28, 'Brendan Renner', 'Astrid_Mann@gmail.com', 'USER', '$2y$12$R9WO6X.h/7pmyqyjxu.Tp.CaGtEI0mLgHT/VgdSy8HfWrLBS3x1.u', NULL, NULL, NULL, NULL, '2024-12-29 08:32:55', 'active', '2024-11-03 08:23:39', NULL, '2024-07-09 14:22:55', '2024-07-22 14:22:55'),
(29, 'Evan Prohaska', 'Wilburn.Borer22@hotmail.com', 'USER', '$2y$12$PF66yIuu4AZvl0AT9B7qJugZV1O97OXCgcxifoq7W4Rcx4rVFbQFK', NULL, NULL, NULL, NULL, '2024-12-29 08:33:04', 'active', '2024-09-10 08:23:53', NULL, '2024-11-03 14:23:04', '2024-11-24 14:23:04'),
(30, 'Mary West', 'Nova16@gmail.com', 'USER', '$2y$12$OuUqaDXM2WPdUctEwlj7MOURI6KjblOQjucWtbLjiR.i20q0gwVxO', NULL, NULL, NULL, NULL, '2024-12-29 08:33:11', 'active', '2024-09-02 08:29:36', NULL, '2024-12-03 14:23:11', '2024-12-08 14:23:11');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `abouts`
--
ALTER TABLE `abouts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blogs`
--
ALTER TABLE `blogs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `f_a_q_s`
--
ALTER TABLE `f_a_q_s`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orders_user_id_foreign` (`user_id`),
  ADD KEY `orders_product_id_foreign` (`product_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reviews_user_id_foreign` (`user_id`),
  ADD KEY `reviews_product_id_foreign` (`product_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `abouts`
--
ALTER TABLE `abouts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `blogs`
--
ALTER TABLE `blogs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=103;

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `f_a_q_s`
--
ALTER TABLE `f_a_q_s`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
