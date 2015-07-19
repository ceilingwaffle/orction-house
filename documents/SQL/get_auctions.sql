use `orction-house-db`;

SELECT 
     a.id as 'auction_id'
	 , a.title as 'auction_title'
     , a.end_date as 'auction_end_date'
     , CASE 
          WHEN w.id IS NOT NULL THEN 'sold'
          WHEN a.end_date > now() THEN 'open'
          ELSE 'expired'
       END as 'auction_status'
     , acat.category as 'auction_category'
     , acon.condition as 'auction_condition'
     , a.image_file_name as 'auction_image'
     , u.username as 'auction_creator_username'
     , CASE 
		  WHEN b2.bid_count IS null THEN 0
          ELSE b2.bid_count
	   END as 'total_bids'
     , b1.amount as 'highest_bid_amount'
     , b1.username as 'highest_bidder_username'
     , f.feedback_type_counts as 'user_feedback_type_counts'
FROM auctions a
left outer join winners w on w.auction_id = a.id
left outer join auction_categories acat on acat.id = a.auction_category_id
left outer join auction_conditions acon on acon.id = a.auction_condition_id
left outer join (
    -- Highest bidder info
    SELECT b.id, b.auction_id, b.amount, b.created_at, u.username
    FROM (
		SELECT b1.*
	    FROM bids AS b1 
        LEFT JOIN bids AS b2
	    ON (b1.auction_id = b2.auction_id AND b1.amount < b2.amount)
	    WHERE b2.amount IS NULL
	) b
    inner join users u on u.id = b.user_id
) b1 on b1.auction_id = a.id
left outer join (
    select b.auction_id, count(b.auction_id) as bid_count
    from bids b
    group by b.auction_id
) b2 on b2.auction_id = a.id
left outer join users u on u.id = a.user_id
left outer join (
    -- 1	Positive
	-- 2	Neutral
	-- 3	Negative
	select t1.user_id, concat_ws(':',group_concat(t1.feedback_type_id ORDER BY t1.feedback_type_id),
                                     group_concat(t1.feedback_type_count ORDER BY t1.feedback_type_id)) 
							     as 'feedback_type_counts'
	from
	(
		SELECT  u.id as 'user_id', ft.id as 'feedback_type_id', count(ft.id) as 'feedback_type_count'
		FROM feedback_types ft
		inner join feedback f on f.feedback_type_id = ft.id
		inner join auctions a on a.id = f.auction_id
		inner join users u on u.id = a.user_id
		group by u.id, f.feedback_type_id
	) t1
	group by user_id
	order by user_id
) f on f.user_id = a.user_id
;

/*
- creator positive feedback percentage




