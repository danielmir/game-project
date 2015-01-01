<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20150101142414 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("SET FOREIGN_KEY_CHECKS=0;");
        $this->addSql("TRUNCATE `user_role`");
        $this->addSql("TRUNCATE `role`");
        $this->addSql("TRUNCATE `subdomains`");
        $this->addSql("TRUNCATE `users`");
        $this->addSql("TRUNCATE `game_contents`");
        $this->addSql("TRUNCATE `games_categories`");
        $this->addSql("TRUNCATE `games`");
        $this->addSql("TRUNCATE `category_contents`");
        $this->addSql("TRUNCATE `categories`");
        $this->addSql("SET FOREIGN_KEY_CHECKS=1;");

        $this->addSql("
            INSERT INTO `users` (`username`, `password`, `is_active`)
            VALUES ('admin', '$2y$12$2Q5hV.piQN1Gnbn0j../Ve8cLCP9.LLdQNGA.LTfnIxgw0bpUbrO.', 1)
        ");
        $this->addSql("
            INSERT INTO `role` (`name`, `role`)
            VALUES ('admin', 'ROLE_ADMIN')
        ");
        $this->addSql("
            INSERT INTO `user_role` (`user_id`, `role_id`)
            VALUES (1, 1)
        ");
        $this->addSql("
            INSERT INTO `subdomains` (`name`, `abbreviation`, `is_active`)
            VALUES ('English', 'en', 1),
            ('Lithuania', 'lt', 1),
            ('Ruassian', 'ru', 1)
        ");
        $this->addSql("
            INSERT INTO `categories` (`displayName`)
            VALUES ('Adventure'),
            ('Action'),
            ('Horror')
        ");
        $this->addSql("
            INSERT INTO `category_contents` (`category_id`, `subdomain_id`, `name`, `is_active`)
            VALUES (1, 1, 'Adventure', 1),
            (1, 2, 'Nuotykių', 1),
            (1, 3, 'Приключения', 1),
            (2, 1, 'Action', 1),
            (2, 2, 'Veiksmo', 1),
            (2, 3, 'действие', 1),
            (3, 1, 'Horror', 1),
            (3, 2, 'Siaubo', 1),
            (3, 3, 'ужас', 1)
        ");
        $this->addSql("
            INSERT INTO `games` (`displayName`)
            VALUES ('Call Of Duty'),
            ('The Tomorrow Children'),
            ('The Vanishing of Ethan Carter'),
            ('Evolve'),
            ('Assassin’s Creed Unity'),
            ('Tearaway Unfolded'),
            ('Life is Strange'),
            ('Ori and the Blind Forest'),
            ('Quantum Break'),
            ('Dead Island')
        ");
        $this->addSql("
            INSERT INTO `game_contents` (`game_id`, `subdomain_id`, `name`, `description`, `link`, `linkDisplay`, `is_active`)
            VALUES (1, 1, 'Call Of Duty', '', 'cod.com', 'en.cod.com', 1),
            (1, 2, 'Call Of Duty', '', 'cod.com', 'lt.cod.com', 1),
            (1, 3, 'Чувство Долга', '', 'cod.com', 'ru.cod.com', 1),
            (2, 1, 'The Tomorrow Children', '', 'ttc.com', 'en.ttc.com', 1),
            (2, 2, 'Rytojaus Vaikai', '', 'ttc.com', 'lt.ttc.com', 1),
            (2, 3, 'Завтра Дети', '', 'ttc.com', 'ru.ttc.com', 1),
            (3, 1, 'The Vanishing of Ethan Carter', '', 'tvec.com', 'en.tvec.com', 1),
            (3, 2, 'The Vanishing of Ethan Carter', '', 'tvec.com', 'lt.tvec.com', 1),
            (3, 3, 'Исчезновение Итан Картер', '', 'tvec.com', 'ru.tvec.com', 1),
            (4, 1, 'Evolve', '', 'ev.com', 'en.ev.com', 1),
            (4, 2, 'Plėtojimas', '', 'ev.com', 'lt.ev.com', 1),
            (4, 3, 'развиваться', '', 'ev.com', 'ru.ev.com', 1),
            (5, 1, 'Assassin’s Creed Unity', '', 'acu.com', 'en.acu.com', 1),
            (5, 2, 'Assassin’s Creed Vienybė', '', 'acu.com', 'lt.acu.com', 1),
            (5, 3, 'Assassins Creed Единство', '', 'acu.com', 'ru.acu.com', 1),
            (6, 1, 'Tearaway Unfolded', '', 'tu.com', 'en.tu.com', 1),
            (6, 2, 'Tearaway Unfolded', '', 'tu.com', 'lt.tu.com', 1),
            (6, 3, 'Tearaway Развернутая', '', 'tu.com', 'ru.tu.com', 1),
            (7, 1, 'Life is Strange', '', 'lis.com', 'en.lis.com', 1),
            (7, 2, 'Gyvenimas keistas', '', 'lis.com', 'lt.lis.com', 1),
            (7, 3, 'Жизнь Странно', '', 'lis.com', 'ru.lis.com', 1),
            (8, 1, 'Ori and the Blind Forest', '', 'oatbf.com', 'en.oatbf.com', 1),
            (8, 2, 'Ori and the Blind Forest', '', 'oatbf.com', 'lt.oatbf.com', 1),
            (8, 3, 'Ori and the Blind Forest', '', 'oatbf.com', 'ru.oatbf.com', 1),
            (9, 1, 'Quantum Break', '', 'qb.com', 'en.qb.com', 1),
            (9, 2, 'Quantum Break', '', 'qb.com', 'lt.qb.com', 1),
            (9, 3, 'Quantum Break', '', 'qb.com', 'ru.qb.com', 1),
            (10, 1, 'Dead Island', '', 'di.com', 'en.di.com', 1),
            (10, 2, 'Negyva sala', '', 'di.com', 'lt.di.com', 1),
            (10, 3, 'Dead Island', '', 'di.com', 'ru.di.com', 1)
        ");
        $this->addSql("
            UPDATE `game_contents` SET `description` = 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Phasellus hendrerit. Pellentesque aliquet nibh nec urna. In nisi neque, aliquet vel, dapibus id, mattis vel, nisi. Sed pretium, ligula sollicitudin laoreet viverra, tortor libero sodales leo, eget blandit nunc tortor eu nibh. Nullam mollis. Ut justo. Suspendisse potenti.'
        ");

    }

    public function down(Schema $schema)
    {

    }
}
