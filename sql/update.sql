UPDATE payment_methods
SET status = FALSE
WHERE expiry_date < DATE_FORMAT(NOW(), '%m/%y')
  AND status = TRUE
  AND card_number IS NOT NULL;