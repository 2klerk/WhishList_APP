--
-- PostgreSQL database dump
--

-- Dumped from database version 12.19
-- Dumped by pg_dump version 12.19

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- Name: pgcrypto; Type: EXTENSION; Schema: -; Owner: -
--

CREATE EXTENSION IF NOT EXISTS pgcrypto WITH SCHEMA public;


--
-- Name: EXTENSION pgcrypto; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION pgcrypto IS 'cryptographic functions';



--
-- Name: add_user(text, text, text, text); Type: PROCEDURE; Schema: public; Owner: postgres
--

CREATE PROCEDURE public.add_user(p_name text, p_surname text, p_email text, p_password text)
    LANGUAGE plpgsql
    AS $$BEGIN
    -- Проверяем, есть ли пользователь с таким email
    IF NOT EXISTS (SELECT 1 FROM users WHERE email = p_email) THEN
        BEGIN
            INSERT INTO users (name, surname, email, password, registration_date)
            VALUES (
                p_name,
                p_surname,
                p_email,
                crypt(p_password, gen_salt('md5')),
                CURRENT_TIMESTAMP
            );
            RAISE NOTICE 'Пользователь успешно добавлен';
        EXCEPTION
            WHEN OTHERS THEN
                RAISE NOTICE 'Ошибка при добавлении пользователя';
        END;
    ELSE
        RAISE NOTICE 'Пользователь с таким email уже существует';
    END IF;
END;$$;


ALTER PROCEDURE public.add_user(p_name text, p_surname text, p_email text, p_password text) OWNER TO postgres;

--
-- Name: add_wish(integer, text, text, text, integer, text, text); Type: PROCEDURE; Schema: public; Owner: postgres
--

CREATE PROCEDURE public.add_wish(p_userid integer, p_name text, p_surname text, p_category text, p_price integer, p_img_path text, p_url text)
    LANGUAGE plpgsql
    AS $$
BEGIN
    INSERT INTO wishlist(
        userid,
        name,
        surname,
        category,
        price,
        img_path,
        url
    )
    VALUES (
        p_userid,
        p_name,
        p_surname,
        p_category,
        p_price,
        p_img_path,
        p_url
    );
END;
$$;


ALTER PROCEDURE public.add_wish(p_userid integer, p_name text, p_surname text, p_category text, p_price integer, p_img_path text, p_url text) OWNER TO postgres;

--
-- Name: get_wishlist(integer); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION public.get_wishlist(p_userid integer) RETURNS jsonb
    LANGUAGE plpgsql
    AS $$
DECLARE
    user_records JSONB;
BEGIN
    SELECT jsonb_agg(
        jsonb_build_object(
            'wishid', w.wishid,
            'name', w.name,
            'category', w.category,
			'price', w.price,
			'img_path', w.img_path,
			'url', w.url
        )
    ) INTO user_records
    FROM wishlist w
    WHERE w.userid = p_userid;

    RETURN COALESCE(user_records, '[]'::JSONB);  -- возвращаем пустой массив JSON, если нет записей
END;
$$;


ALTER FUNCTION public.get_wishlist(p_userid integer) OWNER TO postgres;

--
-- Name: share_whish(); Type: PROCEDURE; Schema: public; Owner: postgres
--

CREATE PROCEDURE public.share_whish()
    LANGUAGE sql
    AS $_$
CREATE OR REPLACE PROCEDURE share_wish(
    p_userid INTEGER,
	p_friendid INTEGER,
	p_whishid INTEGER,
    p_master_name TEXT,
    p_friend_name TEXT
)
LANGUAGE plpgsql
AS $$
BEGIN
    INSERT INTO share_whish (
		userid,
		friendid,
		whishid,
    	master_name,
    	friend_name,
	)
	VALUES (
		p_userid,
		p_friendid,
		p_whishid,
    	p_master_name,
    	p_friend_name,
	);
END;
$$;
$_$;


ALTER PROCEDURE public.share_whish() OWNER TO postgres;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: wishlist; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.wishlist (
    wishid integer NOT NULL,
    userid integer NOT NULL,
    name text NOT NULL,
    surname text,
    category text,
    price integer,
    img_path text,
    url text
);


ALTER TABLE public.wishlist OWNER TO postgres;

--
-- Name: WhishList_whishID_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public."WhishList_whishID_seq"
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public."WhishList_whishID_seq" OWNER TO postgres;

--
-- Name: WhishList_whishID_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public."WhishList_whishID_seq" OWNED BY public.wishlist.wishid;


--
-- Name: friends; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.friends (
    friend_ship_id integer NOT NULL,
    userid1 integer NOT NULL,
    userid2 integer NOT NULL,
    "friendShip_date" date NOT NULL
);


ALTER TABLE public.friends OWNER TO postgres;

--
-- Name: friends_friendShipID_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public."friends_friendShipID_seq"
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public."friends_friendShipID_seq" OWNER TO postgres;

--
-- Name: friends_friendShipID_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public."friends_friendShipID_seq" OWNED BY public.friends.friend_ship_id;


--
-- Name: news; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.news (
    newsid integer NOT NULL,
    news_create date NOT NULL,
    news_edit date,
    header text NOT NULL,
    description text NOT NULL
);


ALTER TABLE public.news OWNER TO postgres;

--
-- Name: news_newsid_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.news_newsid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.news_newsid_seq OWNER TO postgres;

--
-- Name: news_newsid_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.news_newsid_seq OWNED BY public.news.newsid;


--
-- Name: share_whish; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.share_whish (
    shareid integer NOT NULL,
    whishid integer NOT NULL,
    masterid integer NOT NULL,
    friendid integer NOT NULL,
    master_name text,
    friend_name text,
    share_date timestamp with time zone NOT NULL
);


ALTER TABLE public.share_whish OWNER TO postgres;

--
-- Name: share_whish_shareID_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public."share_whish_shareID_seq"
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public."share_whish_shareID_seq" OWNER TO postgres;

--
-- Name: share_whish_shareID_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public."share_whish_shareID_seq" OWNED BY public.share_whish.shareid;


--
-- Name: users; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.users (
    userid integer DEFAULT nextval('public."share_whish_shareID_seq"'::regclass) NOT NULL,
    name text NOT NULL,
    surname text,
    email text NOT NULL,
    password text NOT NULL,
    registration_date date NOT NULL
);


ALTER TABLE public.users OWNER TO postgres;

--
-- Name: friends friend_ship_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.friends ALTER COLUMN friend_ship_id SET DEFAULT nextval('public."friends_friendShipID_seq"'::regclass);


--
-- Name: news newsid; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.news ALTER COLUMN newsid SET DEFAULT nextval('public.news_newsid_seq'::regclass);


--
-- Name: share_whish shareid; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.share_whish ALTER COLUMN shareid SET DEFAULT nextval('public."share_whish_shareID_seq"'::regclass);


--
-- Name: wishlist wishid; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.wishlist ALTER COLUMN wishid SET DEFAULT nextval('public."WhishList_whishID_seq"'::regclass);


--
-- Data for Name: friends; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.friends (friend_ship_id, userid1, userid2, "friendShip_date") FROM stdin;
\.


--
-- Data for Name: news; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.news (newsid, news_create, news_edit, header, description) FROM stdin;
1	2024-07-04	\N	Разработан маршрут для получения новостей о разработке	Можно использовать вместо коммитов)
2	2024-07-04	\N	Тест2	Описание тест2
\.


--
-- Data for Name: share_whish; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.share_whish (shareid, whishid, masterid, friendid, master_name, friend_name, share_date) FROM stdin;
\.


--
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.users (userid, name, surname, email, password, registration_date) FROM stdin;
5	Дмитрий	1	1	$1$iaLEqEBm$GLkZN.rGM9x8I3SlQOT.30	2024-07-03
6	505	51	521	$1$wowoAoP3$7bLgHnjjMZTBVrHY0auAg.	2024-07-03
7	505	51	521	$1$InurLI9B$HpAXFhEoV6efHBpql.h380	2024-07-03
8	Дмитрий	\N	d12m@mail.ru	$1$em5aMPuw$I2U4/c89hVnyfghcxJH/Q.	2024-07-05
9	Foma	\N	foma@mail.ru	$1$xUnQEjSi$DtAbcba2HGjBp0b.LUy6I.	2024-07-05
10	Дмитрий	\N	senior1@yandex.ru	$1$nrUwwtT1$eEekjj0BLyYD8iXJQN43p0	2024-07-05
11	32132151	\N	senior1@yandex.ru	$1$sOtg26WW$ETyftoHPE4iBOPDAymX.K0	2024-07-05
12	321	\N	seni421or1@yandex.ru	$1$a12PBtNe$lWAXe5EFSEsTHYGZzUtIq1	2024-07-05
13	Дмитрий	1	senior1@yandex.ru	$1$IY6QrbwB$prqHgCv1XyDcfzsxjsUwv/	2024-07-05
14	Дмитрий	1	senior1@yandex.ru	$1$4vOMYHTa$d2z6wb74nxxLH6FRhlkFb0	2024-07-05
15	Дмитрий	\N	senior12@yandex.ru	$1$1B0CNM1y$AUP66pXabKaK14/jVBlpI1	2024-07-05
16	Дмитрий	\N	senio321r12@yandex.ru	$1$n3CuOpSb$uPjHh4SKZJSV0ouIjh2.S0	2024-07-05
\.


--
-- Data for Name: wishlist; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.wishlist (wishid, userid, name, surname, category, price, img_path, url) FROM stdin;
3	5	Дмитрий	П	Часы	30000	/img_db	'https://market.yandex.ru/product--skx007j1/652474393?hid=91259&show-uid=17200209995801947551616003&from=search&text=seiko%20skx007&rs=eJxVjTFOwzAYhe2SIhOB5DEqA1anbrGdpInXLkhMFRewnB9LMQ1JVKcCdWKF03AFpApxDZjYQKwsRGVi-aT39J4-WU7Pw2Nv3aplfnXHeU5f3t8OpgFBdLwn
4	5	Дмитрий	П	Часы	6400	/img_db	https://market.yandex.ru/product--muzhskie-amfibiia-720073/1823360993?hid=91259&show-uid=17200223171919622451316005&from=search&text=амфибия&rs=eJwlzjFLAzEUwPGk9mw8UG4sdTB0ytYkbe_o4uDoJH4AwzW90JPaHr0WoZOCKBXBz-Diat06uCgiOhSuuDmpk6CodJK6mNTlP7z8Hi_8AuZX7aV
6	5	1	1	1	1	1	1
\.


--
-- Name: WhishList_whishID_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public."WhishList_whishID_seq"', 6, true);


--
-- Name: friends_friendShipID_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public."friends_friendShipID_seq"', 1, false);


--
-- Name: news_newsid_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.news_newsid_seq', 2, true);


--
-- Name: share_whish_shareID_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public."share_whish_shareID_seq"', 16, true);


--
-- Name: wishlist WhishList_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.wishlist
    ADD CONSTRAINT "WhishList_pkey" PRIMARY KEY (wishid);


--
-- Name: friends friends_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.friends
    ADD CONSTRAINT friends_pkey PRIMARY KEY (friend_ship_id);


--
-- Name: news news_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.news
    ADD CONSTRAINT news_pkey PRIMARY KEY (newsid);


--
-- Name: share_whish share_whish_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.share_whish
    ADD CONSTRAINT share_whish_pkey PRIMARY KEY (shareid);


--
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (userid);


--
-- Name: share_whish friendID; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.share_whish
    ADD CONSTRAINT "friendID" FOREIGN KEY (friendid) REFERENCES public.users(userid) NOT VALID;


--
-- Name: share_whish masterID; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.share_whish
    ADD CONSTRAINT "masterID" FOREIGN KEY (masterid) REFERENCES public.users(userid) NOT VALID;


--
-- Name: wishlist userid; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.wishlist
    ADD CONSTRAINT userid FOREIGN KEY (userid) REFERENCES public.users(userid);


--
-- Name: friends userid1; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.friends
    ADD CONSTRAINT userid1 FOREIGN KEY (userid1) REFERENCES public.users(userid);


--
-- Name: friends userid2; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.friends
    ADD CONSTRAINT userid2 FOREIGN KEY (userid2) REFERENCES public.users(userid);


--
-- Name: share_whish whishID; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.share_whish
    ADD CONSTRAINT "whishID" FOREIGN KEY (whishid) REFERENCES public.wishlist(wishid) NOT VALID;


--
-- PostgreSQL database dump complete
--

