use `orction-house-db`;



-- 1	Positive
-- 2	Neutral
-- 3	Negative
select t1.user_id, concat_ws(':',group_concat(t1.feedback_type_id ORDER BY t1.feedback_type_id),group_concat(t1.feedback_type_count ORDER BY t1.feedback_type_id)) as 'feedback_type_counts'
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
;