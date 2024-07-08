PGDMP     5    ;                |            WishList    12.19    12.19 1    �           0    0    ENCODING    ENCODING        SET client_encoding = 'UTF8';
                      false            �           0    0 
   STDSTRINGS 
   STDSTRINGS     (   SET standard_conforming_strings = 'on';
                      false            �           0    0 
   SEARCHPATH 
   SEARCHPATH     8   SELECT pg_catalog.set_config('search_path', '', false);
                      false            �           1262    16570    WishList    DATABASE     �   CREATE DATABASE "WishList" WITH TEMPLATE = template0 ENCODING = 'UTF8' LC_COLLATE = 'Russian_Russia.1251' LC_CTYPE = 'Russian_Russia.1251';
    DROP DATABASE "WishList";
                postgres    false                        3079    16653    pgcrypto 	   EXTENSION     <   CREATE EXTENSION IF NOT EXISTS pgcrypto WITH SCHEMA public;
    DROP EXTENSION pgcrypto;
                   false            �           0    0    EXTENSION pgcrypto    COMMENT     <   COMMENT ON EXTENSION pgcrypto IS 'cryptographic functions';
                        false    3                        3079    16696    pldbgapi 	   EXTENSION     <   CREATE EXTENSION IF NOT EXISTS pldbgapi WITH SCHEMA public;
    DROP EXTENSION pldbgapi;
                   false            �           0    0    EXTENSION pldbgapi    COMMENT     Y   COMMENT ON EXTENSION pldbgapi IS 'server-side support for debugging PL/pgSQL functions';
                        false    2                        1255    16652     add_user(text, text, text, text) 	   PROCEDURE     �  CREATE PROCEDURE public.add_user(p_name text, p_surname text, p_email text, p_password text)
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
 \   DROP PROCEDURE public.add_user(p_name text, p_surname text, p_email text, p_password text);
       public          postgres    false            !           1255    16745 8   add_wish(integer, text, text, text, integer, text, text) 	   PROCEDURE     �  CREATE PROCEDURE public.add_wish(p_userid integer, p_name text, p_surname text, p_category text, p_price integer, p_img_path text, p_url text)
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
 �   DROP PROCEDURE public.add_wish(p_userid integer, p_name text, p_surname text, p_category text, p_price integer, p_img_path text, p_url text);
       public          postgres    false            "           1255    24935    get_wishlist(integer)    FUNCTION     B  CREATE FUNCTION public.get_wishlist(p_userid integer) RETURNS jsonb
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
 5   DROP FUNCTION public.get_wishlist(p_userid integer);
       public          postgres    false                       1255    16738    share_whish() 	   PROCEDURE     �  CREATE PROCEDURE public.share_whish()
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
 %   DROP PROCEDURE public.share_whish();
       public          postgres    false            �            1259    16599    wishlist    TABLE     �   CREATE TABLE public.wishlist (
    wishid integer NOT NULL,
    userid integer NOT NULL,
    name text NOT NULL,
    surname text,
    category text,
    price integer,
    img_path text,
    url text
);
    DROP TABLE public.wishlist;
       public         heap    postgres    false            �            1259    16597    WhishList_whishID_seq    SEQUENCE     �   CREATE SEQUENCE public."WhishList_whishID_seq"
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 .   DROP SEQUENCE public."WhishList_whishID_seq";
       public          postgres    false    208            �           0    0    WhishList_whishID_seq    SEQUENCE OWNED BY     O   ALTER SEQUENCE public."WhishList_whishID_seq" OWNED BY public.wishlist.wishid;
          public          postgres    false    207            �            1259    16581    friends    TABLE     �   CREATE TABLE public.friends (
    friend_ship_id integer NOT NULL,
    userid1 integer NOT NULL,
    userid2 integer NOT NULL,
    "friendShip_date" date NOT NULL
);
    DROP TABLE public.friends;
       public         heap    postgres    false            �            1259    16579    friends_friendShipID_seq    SEQUENCE     �   CREATE SEQUENCE public."friends_friendShipID_seq"
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 1   DROP SEQUENCE public."friends_friendShipID_seq";
       public          postgres    false    206            �           0    0    friends_friendShipID_seq    SEQUENCE OWNED BY     Y   ALTER SEQUENCE public."friends_friendShipID_seq" OWNED BY public.friends.friend_ship_id;
          public          postgres    false    205            �            1259    24834    news    TABLE     �   CREATE TABLE public.news (
    newsid integer NOT NULL,
    news_create date NOT NULL,
    news_edit date,
    header text NOT NULL,
    description text NOT NULL
);
    DROP TABLE public.news;
       public         heap    postgres    false            �            1259    24837    news_newsid_seq    SEQUENCE     �   CREATE SEQUENCE public.news_newsid_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 &   DROP SEQUENCE public.news_newsid_seq;
       public          postgres    false    216            �           0    0    news_newsid_seq    SEQUENCE OWNED BY     C   ALTER SEQUENCE public.news_newsid_seq OWNED BY public.news.newsid;
          public          postgres    false    217            �            1259    16615    share_whish    TABLE     �   CREATE TABLE public.share_whish (
    shareid integer NOT NULL,
    whishid integer NOT NULL,
    masterid integer NOT NULL,
    friendid integer NOT NULL,
    master_name text,
    friend_name text,
    share_date timestamp with time zone NOT NULL
);
    DROP TABLE public.share_whish;
       public         heap    postgres    false            �            1259    16613    share_whish_shareID_seq    SEQUENCE     �   CREATE SEQUENCE public."share_whish_shareID_seq"
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 0   DROP SEQUENCE public."share_whish_shareID_seq";
       public          postgres    false    210            �           0    0    share_whish_shareID_seq    SEQUENCE OWNED BY     U   ALTER SEQUENCE public."share_whish_shareID_seq" OWNED BY public.share_whish.shareid;
          public          postgres    false    209            �            1259    16571    users    TABLE     �   CREATE TABLE public.users (
    userid integer DEFAULT nextval('public."share_whish_shareID_seq"'::regclass) NOT NULL,
    name text NOT NULL,
    surname text,
    email text NOT NULL,
    password text NOT NULL,
    registration_date date NOT NULL
);
    DROP TABLE public.users;
       public         heap    postgres    false    209            �
           2604    16584    friends friend_ship_id    DEFAULT     �   ALTER TABLE ONLY public.friends ALTER COLUMN friend_ship_id SET DEFAULT nextval('public."friends_friendShipID_seq"'::regclass);
 E   ALTER TABLE public.friends ALTER COLUMN friend_ship_id DROP DEFAULT;
       public          postgres    false    205    206    206            �
           2604    24839    news newsid    DEFAULT     j   ALTER TABLE ONLY public.news ALTER COLUMN newsid SET DEFAULT nextval('public.news_newsid_seq'::regclass);
 :   ALTER TABLE public.news ALTER COLUMN newsid DROP DEFAULT;
       public          postgres    false    217    216            �
           2604    16618    share_whish shareid    DEFAULT     |   ALTER TABLE ONLY public.share_whish ALTER COLUMN shareid SET DEFAULT nextval('public."share_whish_shareID_seq"'::regclass);
 B   ALTER TABLE public.share_whish ALTER COLUMN shareid DROP DEFAULT;
       public          postgres    false    209    210    210            �
           2604    16602    wishlist wishid    DEFAULT     v   ALTER TABLE ONLY public.wishlist ALTER COLUMN wishid SET DEFAULT nextval('public."WhishList_whishID_seq"'::regclass);
 >   ALTER TABLE public.wishlist ALTER COLUMN wishid DROP DEFAULT;
       public          postgres    false    207    208    208            �          0    16581    friends 
   TABLE DATA           V   COPY public.friends (friend_ship_id, userid1, userid2, "friendShip_date") FROM stdin;
    public          postgres    false    206   �?       �          0    24834    news 
   TABLE DATA           S   COPY public.news (newsid, news_create, news_edit, header, description) FROM stdin;
    public          postgres    false    216   �?       �          0    16615    share_whish 
   TABLE DATA           q   COPY public.share_whish (shareid, whishid, masterid, friendid, master_name, friend_name, share_date) FROM stdin;
    public          postgres    false    210   �@                 0    16571    users 
   TABLE DATA           Z   COPY public.users (userid, name, surname, email, password, registration_date) FROM stdin;
    public          postgres    false    204   �@       �          0    16599    wishlist 
   TABLE DATA           a   COPY public.wishlist (wishid, userid, name, surname, category, price, img_path, url) FROM stdin;
    public          postgres    false    208   �B       �           0    0    WhishList_whishID_seq    SEQUENCE SET     E   SELECT pg_catalog.setval('public."WhishList_whishID_seq"', 6, true);
          public          postgres    false    207            �           0    0    friends_friendShipID_seq    SEQUENCE SET     I   SELECT pg_catalog.setval('public."friends_friendShipID_seq"', 1, false);
          public          postgres    false    205            �           0    0    news_newsid_seq    SEQUENCE SET     =   SELECT pg_catalog.setval('public.news_newsid_seq', 2, true);
          public          postgres    false    217            �           0    0    share_whish_shareID_seq    SEQUENCE SET     H   SELECT pg_catalog.setval('public."share_whish_shareID_seq"', 16, true);
          public          postgres    false    209            �
           2606    16607    wishlist WhishList_pkey 
   CONSTRAINT     [   ALTER TABLE ONLY public.wishlist
    ADD CONSTRAINT "WhishList_pkey" PRIMARY KEY (wishid);
 C   ALTER TABLE ONLY public.wishlist DROP CONSTRAINT "WhishList_pkey";
       public            postgres    false    208            �
           2606    16586    friends friends_pkey 
   CONSTRAINT     ^   ALTER TABLE ONLY public.friends
    ADD CONSTRAINT friends_pkey PRIMARY KEY (friend_ship_id);
 >   ALTER TABLE ONLY public.friends DROP CONSTRAINT friends_pkey;
       public            postgres    false    206            �
           2606    24847    news news_pkey 
   CONSTRAINT     P   ALTER TABLE ONLY public.news
    ADD CONSTRAINT news_pkey PRIMARY KEY (newsid);
 8   ALTER TABLE ONLY public.news DROP CONSTRAINT news_pkey;
       public            postgres    false    216            �
           2606    16623    share_whish share_whish_pkey 
   CONSTRAINT     _   ALTER TABLE ONLY public.share_whish
    ADD CONSTRAINT share_whish_pkey PRIMARY KEY (shareid);
 F   ALTER TABLE ONLY public.share_whish DROP CONSTRAINT share_whish_pkey;
       public            postgres    false    210            �
           2606    16578    users users_pkey 
   CONSTRAINT     R   ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (userid);
 :   ALTER TABLE ONLY public.users DROP CONSTRAINT users_pkey;
       public            postgres    false    204                        2606    16634    share_whish friendID    FK CONSTRAINT     �   ALTER TABLE ONLY public.share_whish
    ADD CONSTRAINT "friendID" FOREIGN KEY (friendid) REFERENCES public.users(userid) NOT VALID;
 @   ALTER TABLE ONLY public.share_whish DROP CONSTRAINT "friendID";
       public          postgres    false    210    2802    204            �
           2606    16629    share_whish masterID    FK CONSTRAINT     �   ALTER TABLE ONLY public.share_whish
    ADD CONSTRAINT "masterID" FOREIGN KEY (masterid) REFERENCES public.users(userid) NOT VALID;
 @   ALTER TABLE ONLY public.share_whish DROP CONSTRAINT "masterID";
       public          postgres    false    210    2802    204            �
           2606    16608    wishlist userid    FK CONSTRAINT     q   ALTER TABLE ONLY public.wishlist
    ADD CONSTRAINT userid FOREIGN KEY (userid) REFERENCES public.users(userid);
 9   ALTER TABLE ONLY public.wishlist DROP CONSTRAINT userid;
       public          postgres    false    2802    204    208            �
           2606    16587    friends userid1    FK CONSTRAINT     r   ALTER TABLE ONLY public.friends
    ADD CONSTRAINT userid1 FOREIGN KEY (userid1) REFERENCES public.users(userid);
 9   ALTER TABLE ONLY public.friends DROP CONSTRAINT userid1;
       public          postgres    false    2802    206    204            �
           2606    16592    friends userid2    FK CONSTRAINT     r   ALTER TABLE ONLY public.friends
    ADD CONSTRAINT userid2 FOREIGN KEY (userid2) REFERENCES public.users(userid);
 9   ALTER TABLE ONLY public.friends DROP CONSTRAINT userid2;
       public          postgres    false    206    204    2802            �
           2606    16624    share_whish whishID    FK CONSTRAINT     �   ALTER TABLE ONLY public.share_whish
    ADD CONSTRAINT "whishID" FOREIGN KEY (whishid) REFERENCES public.wishlist(wishid) NOT VALID;
 ?   ALTER TABLE ONLY public.share_whish DROP CONSTRAINT "whishID";
       public          postgres    false    210    2806    208            �      x������ � �      �   �   x�]�A�0E��)f����[x����0�x�J� ^�ύ��.�iڙ����W&K�<I�I��bcp�C�%�+f�X'����yk�7<� xafS��0�8j������Y����H�=ȅ��ՠ�~��6�f��(�nF�>|�.l����g'�ՅX��ĕ-���7����      �      x������ � �         �  x���M��@�5����UŇ�kP�hQq E�P\u�s��da�hp2�H�&���V�W���8������������~�I�j<K���e���`�)�2�ӉN3���O���Oq��8Dq�Oi��h�ʖ��?Z��,!he�-m}*��,Q�H,�}W�f��!b|h�i�nm�:������Iv�=g��,26���qga�ٺ��4�7I�F��G{�l��)�p*��z�`�l��/�Ц�������N�E	z.�p��01�<ի����}(*���̗�ebXC�`T�jL���$�b~>�^n҈���'�{��9|��I,Fw���*�cs�t8��IG�,˳��TØ���e@y�O;A�ȶ{Bf�[m���?��Y3��4��d� �����v��B!|����v�i���ຊD��P	C��ش�5Dlӟ�A,׻��Մ����f�X�A���e���E��(�}�Z-�+�h4~�=(�      �   �  x���;o�P������}�u�"d'uR'��6o!E7��8~��z������Z�"�C8�W@B*�,���Ü�����������~�q�w��Qߟ>��p4É,د���˲8}#�M|7k���q�v��q9�6��/PPTd$�&��ǜ�H&�ԋ
!o"T Bd@"��� �[�$
:�K�����2kn�G�x��JҎk���Ĵ�J_z.�9�lC��ꐆ������b�{�[�]\Ak6�v���X7c=.{���jX����K�Su\������]��Sy�3͎��ጊ��^�R����O䕗��h�cƨ�bjc��y�a�B���$C�d'�kW�׏��MџM��vű:�#�:��O�B���ԙ�.[�wIy/��^Ts�1$`����X��c4J��3ۻ��^�J7r��\N�c��l���j���4d�����<��r��#     