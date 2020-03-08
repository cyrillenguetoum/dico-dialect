# Dico-Dialect Installation guide

*Learning Cameroonian languages easily*

Dico-Dialect builds on [mezzio](https://github.com/mezzio/mezzio) to provide a minimalist
API back-end solution in order to search words in local languages of Cameroon and translate them.

This guide will provide instructions to setup the software on a development machine.

> ### Dependencies
> Make sure you have installed docker, docker-compose, git, php and composer in your computer.
> On Windows systems the installation is relatively straightfoward since you just
> have to download and install 'Docker Destop' software. same for git, php and composer. Linux
> users must have a good knowledge of command line to install the softwares listed above.

## Getting started

Clone the project into your computer by typing in your cmd line interface:
```bash
git clone https://vetcoder/dico-dialect dico
```
Navigate through command line into the project directory (dico), then Download and install
 the dependencies:
```bash
composer install
```
Now Create a copy of the file 'docker-compose.yml.dist' and rename it into 'docker-compose.yml' in the root folder of the project. Open it and edit the line containing: '- MYSQL_ROOT_PASSWORD=XXXX' replacing the XXXX with a password of your one. Replace the XXXXX in the line container 'MYSQL_DATABASE=XXXXX' with any string you want to be the name of the database persisting datas.

We're almost done. Create a copy of the file 'config/autoload/doctrine.local.php.dist' and rename it by removing the .dist extension. Open the file and edit the line containing "'url' => 'mysql://your_username:your_password@db/database'" by replacing 'your_username' with 'root', 'your_password' and 'your_database' with the password and database name you provided during the editing of the docker-compose.yml file. After editing the file rename it by removing the .dist extension. That's all.

Now create the NAT netword(for windows users) or the Bridged Network (for linux users). Just type in the cmd line interface:
```bash
docker network create app-network                   //for linux users
docker network create --driver nat app-network      //for Windows users
```
Then finally launch all the containers with this command:
```bash
docker-compose up
```
> ### Testing
> you could type 'docker-compose up -d' if you don't want to check the verbose output.
> The launching process achieved open your browser and visit 'http://localhost:8080' just to verify
> that everything worked as expected. You should see the UI of adminer a well known alternative to
> PHPmyAdmin for administering databases. The url of our API is located at 'http://localhost:80',
> but don't consult it for now since we need additional configuration for everything to work properly.

## Configuring the database

Navigate with the browser to the 'http://localhost:8080' adminer's interface. You could log in by using the credentials you had setup during configuration of the docker-compose.yml file. When logged in, select the database created by docker-compose and click on the 'SQL Command' button in order to populate the database with table and test datas. Then paste into the textarea the following text:

```sql
SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

CREATE TABLE `Entry` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `word` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `typology` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `definition` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `Entry` (`id`, `word`, `typology`, `definition`) VALUES
(1,	'mekan',	'n.c., plur.',	'Plats, couvert'),
(2,	'Kandon',	'n.c., sing',	'Doigt de Plantain, Pied de plantain, Regime de plantain'),
(3,	'lekan',	'n.c., sing',	'Sorcellerie, Pratiques douteuses'),
(4,	'spoon',	'n.c., sing',	'Cuillere, Fait partie de la vaisselle, Cf mekan.'),
(5,	'nkap',	'n.c, sing.',	'Argent pris dans sa globalite, Monnaie, Piece de monnaie, Billet de banque.'),
(6,	'tree',	'n.c, sing.',	'le pere de ..., le pere.'),
(7,	'koumbou',	'n.c., sing.',	'Recipient creux, Soupiere, petite marmite'),
(8,	'metua',	'n.c., sing.',	'Marche, Activite de vente, Lieu d\'un marche');
```

## Testing

Cool. At this stage we are ready to go consume the API.
Initialize the search index by visiting the url: http://localhost:80/api/search .
To retrieve a word entry specifications in the dictionnary your have to type an address like: 'http://localhost:80/api/entries/1', then you will see its definition and other metadatas displaying in json/XML (depending on what you have populated the Accept:* header with in your request).
You can also search a word in the dictionnary. For example type: http://localhost:80/search?word="mekan" and see the results displayed.

The Api actually provide four routes, with some of them supporting many request methods(GET, POST, PATCH, DELETE):
- http://localhost/api/entries/id   lauched without request method (GET is the default) with id as the unique identifier of an entry in the dictionary, you will get an entry with its caracteristics; With PATCH or DELETE requests methods you will modify or delete an entry. You will provide datas in the request body for the modified entry.
- http://localhost/api/entries this route permits you to retrieve all the entries in a paginated way; Additional query (?page=5 for example) permits to select a specific page in case of numerous entries; With the POST request method and datas of the new entry encoded in the data-form in the request body, you will create a new entry.
- http://localhost (the welcome message);
- http://localhost/api/search Without any query parameter this route launches indexation process. Indexes are saved in the director 'data/search'. You need to launch the indexation process at least once in order to perform queries. To perform a word searching type something like: http://localhost/api/search?word=mekan , where 'mekan' is the word to find in the indexes. Research hits will be presented to you therefore.

## TODO

- We need to populate the database with datas
- Develop front-ends solution to consume this API (desktop apps, mobile apps or webpage).
- We need to find a way to encode special characters of the ngiemboon language.
- Write at least unit tests for the code.
