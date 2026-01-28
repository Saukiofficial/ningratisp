
# Menu
## Dashboard
    - contains stats (this month) widget Gross revenue, total discounts net revenue, MRR and ARR
    - contains chart (last 12 months) revenue overview, net revenue, 
    - contains chart payment by methods (last 30 days) virtual account status and Account Receivable Aging
## Information Mikrotik : fetch data from api mikrotik
    - contains widget Upload speed, Download speed
    - contains widget online, offline and expired/isolir users
    - contains list offline users
## Whatsapp Information : fetch data from unofficial api whatsapp 
## Customer Connections : customer connection tracker based on PING result to customer ip address
    - action button "Run Ping"
    - list table contains customer data, ip address, status, ping count, packget loss, avg rtt
    - list table action button "Ping" specific customer
## Master
### Discounts : dicount data configuration
    - list table contains name, code, type, value, applicable to, active?
    - form create has 3 sections :
        - discount information contains name, code (generate/input manual), start date, end date and description
        - application details contains type (fixed/percentage), applicable to (invoice, customer, package), customer category (normal, free forever, load/adjustment), specific package, value (Rp / %) and max discount amount
        - additional settings contains usage limit (max user can reclaimed) and user limit (max total user can claim)
    - form view and edit similar form create, on form view has relation manager to list customers who claimed the voucher
### Packages : master package for customer subscription
    - list table contains name, PPP Profile, price, duration months, is active
    - form create contains name, select PPP Profile, duration months, price, description and is active
    - form view and edit similar form create
### Template Messages : master for template message/email that will used for customer information like invoice, hotspot voucher, promo, etc. using rich text editor
    - list table contains name, used at
    - form create contains name, used at (related to model liek voucher, invoice, promo), content using rich text editor
    - form view and edit similar form create
### Customers : master data for customers
    - contains header widget (total customers, active customer and isolir customer/expired)
    - has action header button "Sync Customer to Mikrotik" to grab customer data that added through mikrotik
    - list table contains billing number, username, full name, package, isolir at and category
    - form create (on-going)
    - form view has 2 section:
        - basic information contains billing number, username, full name, package, active invoice and category (free forever, normal)
        - relation manager section contains list table :
            - active invoices (unpaid/half payment invoice) contains package, invoice number, total amount, discount amount, paid amount, due date and status. active invoice also action button create invoice that select package, due date and total invoice this is for adjustment or loan invoice. active invoice also has manual payment for specific invoice or selected some invoice and bulk payments
            - all invoices contains similar active invoice
            - payments records
            - package contains list package that has been subscribed or change package
### Payment Methods : master data for list available payment methods (midtrans payment gateway)
    - list table contains code, name, logo, description, category (e-wallet, VA, bank transfer and cash)
    - form create contains code, name, logo, description, category
    - form view and edit similar form create
### PPP Profiles : master PPP Profile based on mikrotik ppp profile
    - has action header button "Sync Profile to Mikrotik"
    - list contains profile name, rate limit and is active
### Users : default user data

## Transactions
### Invoices : invoice records
    - list table contains invoice number, customer, total amount, discount amount, paid amount, remaining, payment status, invoice date and due date
    - list table action button "Record payment" for manual payment with field amount, payment method, reference id and file proof
    - form view has relation manager into invoice items and allocation manager
### Payments : payment records for invoice and hotspot voucher, both can manual payment of automatic payment (payment gateway notif)
    - list table contains payment type, total amount, reference id, payment datetime and description
### Vouchers : generated hotspot voucher record
    - list table contains order id, code, expired at, whatsapp number, description, druation payment status (paid/unpaid) and price
    - list table action button "Send To Whatsapp" send manual to whatsapp if number provided

## Logs
### Log Midtrans
### Log Mikrotiks