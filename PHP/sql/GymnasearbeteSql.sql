CREATE DATABASE stratego;
USE stratego;

CREATE TABLE user (id INT NOT NULL PRIMARY KEY auto_increment,
                    uname varchar(30),
                    name varchar(30),
                    password varchar(255),
                    epost varchar(100));

CREATE TABLE game (id INT NOT NULL PRIMARY KEY auto_increment,
                      timePerTurn LONG,
                      user1 INT,
                      user2 INT,
                      FOREIGN KEY (user1) REFERENCES user(id),
                      FOREIGN KEY (user2) REFERENCES user(id)
                       );


/**CREATE TABLE user_game (uid INT,
                  gameId INT,
                  FOREIGN KEY (uid) REFERENCES user(id),
                  FOREIGN KEY (gameId) REFERENCES game(id));´*/

CREATE TABLE state (timeStamp TIMESTAMP,
                    id INT NOT NULL PRIMARY KEY auto_increment,
                    gameId INT ,
                    uid INT,
                    stateNr INT,
                    FOREIGN KEY (uid) REFERENCES user(id),
                    FOREIGN KEY (gameId) REFERENCES game(id));
                    /*--uid = vem tur är det*/

CREATE TABLE characters(id INT NOT NULL PRIMARY KEY auto_increment,
                        type INT,
                        uid INT,
                        gameId INT,
                        visebel BOOLEAN,
                        FOREIGN KEY (uid) REFERENCES user(id),
                        FOREIGN KEY (gameId) REFERENCES game(id)
                       );/*
                          1	Fältmarskalk	1
                          2	General	      1
                          3	Överste	      2
                          4	Major	        3
                          5	Kapten	      4
                          6	Löjtnant	    4
                          7	Sergeant	    4
                          8	Minör	        5
                          9	Spejare	      8
                          10 Spion	      1

                          11 Bomb         6
                          12 Flagga       1


                        */

                        /*
                        x==1 && y == 1 ; längstupp i vänstra hörnet

                         */

CREATE TABLE tiles (id INT NOT NULL PRIMARY KEY auto_increment,
                    type INT,
                    x_cord INT,
                    y_cord INT,
                    gameId INT,
                    stateId INT,
                    characters INT,
                    FOREIGN KEY (gameId) REFERENCES game(id),
                    FOREIGN KEY (stateId) REFERENCES state(id),
                    FOREIGN KEY (characters) REFERENCES characters(id)
);                  /*--type: 1 == vatten , 0 == land*/

