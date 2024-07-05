CREATE OR REPLACE FUNCTION get_wishlist(p_userid INTEGER)
RETURNS JSONB AS $$
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
$$ LANGUAGE plpgsql;
