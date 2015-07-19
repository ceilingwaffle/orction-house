use `orction-house-db`;

-- 1	Positive
-- 2	Neutral
-- 3	Negative
select t1.user_id, group_concat(t1.feedback_type_count) as 'feedback_type_counts'
from
(
    SELECT u.id as 'user_id', f.feedback_type_id, count(f.feedback_type_id) as 'feedback_type_count'
    FROM feedback f
    inner join feedback_types ft on ft.id = f.feedback_type_id
    inner join auctions a on a.id = f.auction_id
    inner join users u on u.id = a.user_id
    group by u.id, f.feedback_type_id, ft.id
) t1
group by t1.user_id
;