create table `request` (
    `id` bigint primary key not null auto_increment,
    `scheme` varchar(32) not null,
    `hostname` varchar(100) not null,
    `port` smallint unsigned not null default 80,
    `pathname` varchar(2000) not null,
    `query` varchar(2000),
    `fragment` varchar(2000),
    `agent` varchar(60),
    `created_at` timestamp not null default current_timestamp
);
