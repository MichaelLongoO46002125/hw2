CREATE TABLE persons (
	id INTEGER AUTO_INCREMENT PRIMARY KEY,
	email VARCHAR(320) UNIQUE,
	name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255) NOT NULL,
	phone_number VARCHAR(16) NOT NULL,
	password VARCHAR(64) NOT NULL,
	created_at TIMESTAMP NOT NULL DEFAULT 0,
	updated_at TIMESTAMP NOT NULL DEFAULT 0
) ENGINE=InnoDB;

CREATE TABLE employees (
	person_id INTEGER PRIMARY KEY,
	salary DECIMAL(8,2) NOT NULL,
	job VARCHAR(64) NOT NULL,
	duty_start TIME NOT NULL,
	duty_end TIME NOT NULL,
	created_at TIMESTAMP NOT NULL DEFAULT 0,
	updated_at TIMESTAMP NOT NULL DEFAULT 0,
	INDEX idx_employee_person_id(person_id),
	FOREIGN KEY (person_id) REFERENCES persons(id)
) ENGINE=InnoDB;

CREATE TABLE cookies (
	id INTEGER AUTO_INCREMENT PRIMARY KEY,
	token VARCHAR(64) NOT NULL,
	expires DATE NOT NULL,
	person_id INTEGER NOT NULL,
	created_at TIMESTAMP NOT NULL DEFAULT 0,
	updated_at TIMESTAMP NOT NULL DEFAULT 0,
	INDEX idx_cookie_person_id (person_id),
	FOREIGN KEY (person_id) REFERENCES persons(id)
) ENGINE=InnoDB;

CREATE TABLE contents (
	id INTEGER AUTO_INCREMENT PRIMARY KEY,
	title VARCHAR(255) NOT NULL,
	description TEXT NOT NULL,
	date DATETIME NOT NULL,
	image_url VARCHAR(255),
	created_at TIMESTAMP NOT NULL DEFAULT 0,
	updated_at TIMESTAMP NOT NULL DEFAULT 0
);

CREATE TABLE tags (
	id INTEGER AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(64) NOT NULL UNIQUE,
	created_at TIMESTAMP NOT NULL DEFAULT 0,
	updated_at TIMESTAMP NOT NULL DEFAULT 0
);

CREATE TABLE content_tag (
	content_id INTEGER,
	tag_id INTEGER,
	created_at TIMESTAMP NOT NULL DEFAULT 0,
	updated_at TIMESTAMP NOT NULL DEFAULT 0,
	PRIMARY KEY(content_id, tag_id),
	INDEX idx_ct_content(content_id),
	INDEX idx_ct_tag(tag_id),
	FOREIGN KEY (content_id) REFERENCES contents(id),
	FOREIGN KEY (tag_id) REFERENCES tags(id)
);

CREATE TABLE favorites (
	person_id INTEGER,
	content_id INTEGER,
	created_at TIMESTAMP NOT NULL DEFAULT 0,
	updated_at TIMESTAMP NOT NULL DEFAULT 0,
	PRIMARY KEY (person_id, content_id),
	INDEX idx_fav_person(person_id),
	INDEX idx_fav_content(content_id),
	FOREIGN KEY (person_id) REFERENCES persons(id),
	FOREIGN KEY (content_id) REFERENCES contents(id)
);

CREATE TABLE room_types(
	id INTEGER AUTO_INCREMENT PRIMARY KEY,
	type VARCHAR(255) NOT NULL,
	accomodation VARCHAR(255) NOT NULL,
	created_at TIMESTAMP NOT NULL DEFAULT 0,
	updated_at TIMESTAMP NOT NULL DEFAULT 0,
	UNIQUE(type,accomodation)
) ENGINE=InnoDB;

CREATE TABLE rooms(
	room_number VARCHAR(4) PRIMARY KEY,
	room_type_id INTEGER NOT NULL,
	person_number INTEGER NOT NULL,
	matrimonial_bed INTEGER NOT NULL,
	single_bed INTEGER NOT NULL,
	wifi BOOLEAN NOT NULL,
	wifi_free BOOLEAN NOT NULL,
	minibar BOOLEAN  NOT NULL,
	soundproofing BOOLEAN NOT NULL,
	swimming_pool BOOLEAN NOT NULL,
	private_bathroom BOOLEAN NOT NULL,
	air_conditioning BOOLEAN NOT NULL,
	sqm DECIMAL(5,2) NOT NULL,
	nightly_fee DECIMAL(8,2) NOT NULL,
	description TEXT NOT NULL,
	created_at TIMESTAMP NOT NULL DEFAULT 0,
	updated_at TIMESTAMP NOT NULL DEFAULT 0,
	INDEX idx_room_type(room_type_id),
	FOREIGN KEY (room_type_id) REFERENCES room_types(id)
) ENGINE=InnoDB;

CREATE TABLE room_photos(
	id INTEGER AUTO_INCREMENT PRIMARY KEY,
	room_id VARCHAR(4) NOT NULL,
	photo_path VARCHAR(256) NOT NULL,
	created_at TIMESTAMP NOT NULL DEFAULT 0,
	updated_at TIMESTAMP NOT NULL DEFAULT 0,
	INDEX idx_rphotos_room_id(room_id),
	FOREIGN KEY (room_id) REFERENCES rooms(room_number)
) ENGINE=InnoDB;

CREATE TABLE rents(
	id INTEGER AUTO_INCREMENT PRIMARY KEY,
	person_id INTEGER NOT NULL,
	room_id VARCHAR(4) NOT NULL,
	night_stay INTEGER NOT NULL,
	nightly_fee DECIMAL(8,2) NOT NULL,
	check_in DATE NOT NULL,
	check_out DATE NOT NULL,
	created_at TIMESTAMP NOT NULL DEFAULT 0,
	updated_at TIMESTAMP NOT NULL DEFAULT 0,	
	INDEX idx_rent_person_id (person_id),
	INDEX idx_rent_room_id (room_id),
	FOREIGN KEY (person_id) REFERENCES persons(id),
	FOREIGN KEY (room_id) REFERENCES rooms(room_number)
) ENGINE=InnoDB;

DELIMITER //
CREATE TRIGGER tr_tag_uppercase
BEFORE INSERT ON tags
FOR EACH ROW 
BEGIN
	IF NEW.name IS NOT NULL THEN 
		SET NEW.name = UPPER(NEW.name);
	END IF;
END //
DELIMITER ;


insert into contents (title, image_url, date, description) values 
	(
		'Example 1',
		'resources/images/example.png',
		'2021-03-08 08:00:00',
		'Esempio descrizione'
	),
	(
		'Example 2',
		'resources/images/example.png',
		'2021-03-09 08:00:00',
		'Esempio descrizione'
	),
	(
		'Example 3',
		'resources/images/example.png',
		'2021-03-10 08:00:00',
		'Esempio descrizione'
	),
	(
		'Example 4',
		'resources/images/example.png',
		'2021-03-11 08:00:00',
		'Esempio descrizione'
	),
	(
		'Example 5',
		'resources/images/example.png',
		'2021-03-11 10:00:00',
		'Esempio descrizione'
	),
	(
		'Example 6',
		'resources/images/example.png',
		'2021-03-12 08:00:00',
		'Esempio descrizione'
	),
	(
		'Example 7',
		'resources/images/example.png',
		'2021-03-13 08:00:00',
		'Esempio descrizione'
	),
	(
		'Example 8',
		'resources/images/example.png',
		'2021-03-14 08:00:00',
		'Esempio descrizione'
	),
	(
		'Parcheggio gratuito',
		'resources/images/parcheggio.png',
		'2021-03-18 08:00:00',
		'Dal 20/03/2021 al 31/03/2021 il parcheggio sarà gratuito per chi ha già affittato una camera o per chi affitta una camera in questo intervallo di tempo.'
	),
	(
		'Riapertura della piscina',
		'resources/images/piscina.jpg',
		'2021-03-21 08:00:00',
		'La ristrutturazione della piscina è terminata per tale motivo inauguriamo la nuova riapertura della piscina.'
	),
	(
		'Specialità del giorno',
		'resources/images/pietanza_carbonara.jpg',
		'2021-03-22 08:00:00',
		'Oggi la specialità del giorno è la carbonara.\r\nChi ordina la specialità del giorno riceverà il dessert in omaggio.'
	),
	(
		'Suite Matrimoniale sconto del 50%',
		'resources/images/matrimonial3.jpg',
		'2021-03-22 09:00:00',
		'Solo per oggi affitta una suite matrimoniale per almeno tre notti e ottieni uno sconto del 50%.'
	);
	
insert into tags (name) values ('Example'), ('News'), ('Offerta'), ('Ristorazione');

insert into content_tag (content_id, tag_id) values (12,3), (11,4), (10,2), (10,1), (9,2), (8,1), (7,1), (6,1),(5,1), (4,1), (3,1), (2,1), (1,1);

insert into room_types (type, accomodation) values ('Standard', 'Singola');
insert into room_types (type, accomodation) values ('Standard', 'Doppia singola');
insert into room_types (type, accomodation) values ('Standard', 'Matrimoniale');
insert into room_types (type, accomodation) values ('Standard', 'Matrimoniale + Doppia singola');
insert into room_types (type, accomodation) values ('Superior', 'Singola');
insert into room_types (type, accomodation) values ('Superior', 'Doppia singola');
insert into room_types (type, accomodation) values ('Superior', 'Matrimoniale');
insert into room_types (type, accomodation) values ('Superior', 'Matrimoniale + Singola');
insert into room_types (type, accomodation) values ('Suite', 'Singola');
insert into room_types (type, accomodation) values ('Suite', 'Doppia singola');
insert into room_types (type, accomodation) values ('Suite', 'Matrimoniale');
insert into room_types (type, accomodation) values ('Suite', 'Quadrupla');

insert into rooms(room_number, room_type_id, person_number, matrimonial_bed, single_bed, wifi, wifi_free, minibar, soundproofing, swimming_pool, private_bathroom, air_conditioning, sqm, nightly_fee, description)
	values	('100A', 1, 1, 0, 1, 1, 0, 0, 1, 0, 1, 1, 28, 50, 'Descrizione'), 
			('101A', 2, 2, 0, 2, 1, 1, 0, 1, 0, 1, 1, 30, 80, 'Descrizione'),
			('102A', 3, 2, 1, 0, 1, 1, 1, 1, 0, 1, 1, 30, 70, 'Descrizione'),
			('103A', 4, 4, 1, 2, 1, 1, 1, 1, 0, 1, 1, 35, 130, 'Lorem ipsum dolor sit amet, consectetur adipisci elit, sed do eiusmod tempor incidunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrum exercitationem ullamco laboriosam, nisi ut aliquid ex ea commodi consequatur. Duis aute irure reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint obcaecat cupiditat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.'),
			('200A', 5, 1, 0, 1, 1, 1, 1, 1, 0, 1, 1, 30, 90, 'Lorem ipsum dolor sit amet, consectetur adipisci elit, sed do eiusmod tempor incidunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrum exercitationem ullamco laboriosam, nisi ut aliquid ex ea commodi consequatur. Duis aute irure reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint obcaecat cupiditat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.'),
			('201A', 6, 2, 0, 2, 1, 0, 1, 1, 0, 1, 1, 35, 140, 'Lorem ipsum dolor sit amet, consectetur adipisci elit, sed do eiusmod tempor incidunt ut labore et dolore magna aliqua. Ut enim ad minim veniam'),
			('202A', 7, 2, 1, 0, 1, 0, 1, 1, 1, 1, 1, 35, 130, 'Lorem ipsum dolor sit amet, consectetur adipisci elit, sed do eiusmod tempor incidunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrum exercitationem ullamco laboriosam, nisi ut aliquid ex ea commodi consequatur.'),
			('203A', 8, 3, 1, 1, 1, 1, 1, 1, 1, 1, 1, 40, 200, 'Lorem ipsum dolor sit amet, consectetur adipisci elit, sed do eiusmod tempor incidunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrum exercitationem ullamco laboriosam, nisi ut aliquid ex ea commodi consequatur. Duis aute irure reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint obcaecat cupiditat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.'),
			('100B', 9, 1, 0, 1, 1, 1, 1, 1, 1, 1, 1, 35, 200, 'Lorem ipsum dolor sit amet, consectetur adipisci elit, sed do eiusmod tempor incidunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrum exercitationem ullamco laboriosam, nisi ut aliquid ex ea commodi consequatur. Duis aute irure reprehenderit in voluptate velit esse cillum dolore'),
			('101B',10, 2, 0, 2, 1, 1, 1, 1, 1, 1, 1, 35, 270, 'Lorem ipsum dolor sit amet, consectetur adipisci elit, sed do eiusmod tempor incidunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrum exercitationem ullamco laboriosam, nisi ut aliquid ex ea commodi consequatur. Duis aute irure reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint obcaecat cupiditat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.'),
			('200B',11, 2, 1, 0, 1, 1, 1, 1, 1, 1, 1, 35, 260, 'Lorem ipsum dolor sit amet, consectetur adipisci elit, sed do eiusmod tempor incidunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrum exercitationem ullamco laboriosam, nisi ut aliquid ex ea commodi consequatur. Duis aute irure reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.'),
			('201B',12, 4, 1, 2, 1, 1, 1, 1, 1, 1, 1, 50, 400, 'Descrizione........');
			
insert into room_photos (room_id, photo_path) values ('100A', 'resources/images/single1.jpg'),
															('200A', 'resources/images/single2.jpg'),
															('100B', 'resources/images/single3.jpg'),
															('101A', 'resources/images/double1.jpg'),
															('201A', 'resources/images/double2.jpg'),
															('101B', 'resources/images/double3.jpg'),
															('102A', 'resources/images/matrimonial1.jpg'),
															('202A', 'resources/images/matrimonial2_1.jpg'),
															('202A', 'resources/images/matrimonial2_2.jpg'),
															('200B', 'resources/images/matrimonial3.jpg'),
															('103A', 'resources/images/matrimonial_with_double.jpg'),
															('203A', 'resources/images/matrimonial_with_single.jpg'),
															('201B', 'resources/images/quadruple_1.jpg'),
															('201B', 'resources/images/quadruple_2.jpg');

#Tutte le password equivalgono a Prova123
insert into persons (email, name, last_name, phone_number, password) values 
	('admin@admin.com', 'Admin', 'Admin', '+390000000', '$2y$10$FyzJMeBsJoWGnygH2ewCrurUG6CH6cMp2ofBZ94zNpAcws2NAwm5m'),
	('user@user.com', 'User', 'User', '+390000001', '$2y$10$XOA33SWkgefkDyxR6lAb7eCVW9r.FT48HSRFUSNhb.P543/CnD9Qm');
	
insert into employees (person_id, salary, job, duty_start, duty_end) values (1, '3000', 'ADMIN', '00:00:00', '08:00:00');

		