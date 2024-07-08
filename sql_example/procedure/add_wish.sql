


-- Создание или замена процедуры
CREATE OR REPLACE PROCEDURE add_wish(
    p_userid INTEGER,
    p_name TEXT,
    p_surname TEXT,
    p_category TEXT,
    p_price INTEGER,
    p_img_path TEXT,
    p_url TEXT
)
LANGUAGE plpgsql
AS $$
BEGIN
    INSERT INTO wishlist (
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

-- Изменение владельца процедуры
ALTER PROCEDURE public.add_wish OWNER TO postgres;
