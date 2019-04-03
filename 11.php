<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<xs:schema version="1.0" xmlns:xs="http://www.w3.org/2001/XMLSchema">
    <xs:element name="storeReportItemDto" type="storeReportItemDto"/>
    <xs:complexType name="storeReportItemDto">
        <xs:sequence>
            <xs:element name="ndsPercent" type="xs:decimal" minOccurs="0"/>
            <xs:element name="productCategory" type="xs:string" minOccurs="0"/>
            <xs:element name="productGroup" type="xs:string" minOccurs="0"/>
            <xs:element name="product" type="xs:string" minOccurs="0"/>
            <xs:element name="secondaryAccount" type="xs:string" minOccurs="0"/>
            <xs:element name="primaryStore" type="xs:string" minOccurs="0"/>
            <xs:element name="documentNum" type="xs:string" minOccurs="0"/>
            <xs:element name="expenseAccount" type="xs:string" minOccurs="0"/>
            <xs:element name="revenueAccount" type="xs:string" minOccurs="0"/>
            <xs:element name="documentComment" type="xs:string" minOccurs="0"/>
            <xs:element name="documentId" type="xs:string" minOccurs="0"/>
            <xs:element name="documentType" type="documentType" minOccurs="0"/>
            <xs:element name="incoming" type="xs:boolean"/>
            <xs:element name="type" type="transactionType" minOccurs="0"/>
            <xs:element name="date" type="xs:string" minOccurs="0"/>
            <xs:element name="operationalDate" type="xs:string" minOccurs="0"/>
            <xs:element name="cost" type="xs:decimal" minOccurs="0"/>
            <xs:element name="secondEstimatedPurchasePrice" type="xs:decimal" minOccurs="0"/>
            <xs:element name="firstEstimatedPurchasePrice" type="xs:decimal" minOccurs="0"/>
            <xs:element name="documentSum" type="xs:decimal" minOccurs="0"/>
            <xs:element name="secondaryAmount" type="xs:decimal" minOccurs="0"/>
            <xs:element name="amount" type="xs:decimal" minOccurs="0"/>
            <xs:element name="sumWithoutNds" type="xs:decimal" minOccurs="0"/>
            <xs:element name="sumNds" type="xs:decimal" minOccurs="0"/>
            <xs:element name="sum" type="xs:decimal" minOccurs="0"/>
        </xs:sequence>
    </xs:complexType>
    <xs:simpleType name="transactionType">
        <xs:restriction base="xs:string">
            <xs:enumeration value="OPENING_BALANCE"/>
            <xs:enumeration value="CUSTOM"/>
            <xs:enumeration value="CASH"/>
            <xs:enumeration value="PREPAY_CLOSED"/>
            <xs:enumeration value="PREPAY"/>
            <xs:enumeration value="PREPAY_RETURN"/>
            <xs:enumeration value="PREPAY_CLOSED_RETURN"/>
            <xs:enumeration value="DISCOUNT"/>
            <xs:enumeration value="CARD"/>
            <xs:enumeration value="CREDIT"/>
            <xs:enumeration value="PAYIN"/>
            <xs:enumeration value="PAYOUT"/>
            <xs:enumeration value="PAY_COLLECTION"/>
            <xs:enumeration value="CASH_CORRECTION"/>
            <xs:enumeration value="INVENTORY_CORRECTION"/>
            <xs:enumeration value="STORE_COST_CORRECTION"/>
            <xs:enumeration value="CASH_SURPLUS"/>
            <xs:enumeration value="CASH_SHORTAGE"/>
            <xs:enumeration value="PENALTY"/>
            <xs:enumeration value="BONUS"/>
            <xs:enumeration value="INVOICE"/>
            <xs:enumeration value="NDS_INCOMING"/>
            <xs:enumeration value="NDS_SALES"/>
            <xs:enumeration value="SALES_REVENUE"/>
            <xs:enumeration value="OUTGOING_INVOICE"/>
            <xs:enumeration value="OUTGOING_INVOICE_REVENUE"/>
            <xs:enumeration value="RETURNED_INVOICE"/>
            <xs:enumeration value="RETURNED_INVOICE_REVENUE"/>
            <xs:enumeration value="WRITEOFF"/>
            <xs:enumeration value="SESSION_WRITEOFF"/>
            <xs:enumeration value="TRANSFER"/>
            <xs:enumeration value="TRANSFORMATION"/>
            <xs:enumeration value="TARIFF_HOUR"/>
            <xs:enumeration value="ON_THE_HOUSE"/>
            <xs:enumeration value="ADVANCE"/>
            <xs:enumeration value="INCOMING_SERVICE"/>
            <xs:enumeration value="OUTGOING_SERVICE"/>
            <xs:enumeration value="INCOMING_SERVICE_PAYMENT"/>
            <xs:enumeration value="OUTGOING_SERVICE_PAYMENT"/>
            <xs:enumeration value="CLOSE_AT_EMPLOYEE_EXPENSE"/>
            <xs:enumeration value="INCENTIVE_PAYMENT"/>
            <xs:enumeration value="TARIFF_PERCENT"/>
            <xs:enumeration value="SESSION_ACCEPTANCE"/>
            <xs:enumeration value="EMPLOYEE_CASH_PAYMENT"/>
            <xs:enumeration value="EMPLOYEE_PAYMENT"/>
            <xs:enumeration value="INVOICE_PAYMENT"/>
            <xs:enumeration value="OUTGOING_DOCUMENT_PAYMENT"/>
            <xs:enumeration value="OUTGOING_SALES_DOCUMENT_PAYMENT"/>
            <xs:enumeration value="PRODUCTION"/>
            <xs:enumeration value="SALES_RETURN_PAYMENT"/>
            <xs:enumeration value="SALES_RETURN_WRITEOFF"/>
            <xs:enumeration value="DISASSEMBLE"/>
        </xs:restriction>
    </xs:simpleType>
    <xs:simpleType name="documentType">
        <xs:restriction base="xs:string">
            <xs:enumeration value="INCOMING_INVOICE"/>
            <xs:enumeration value="INCOMING_INVENTORY"/>
            <xs:enumeration value="INCOMING_SERVICE"/>
            <xs:enumeration value="OUTGOING_SERVICE"/>
            <xs:enumeration value="WRITEOFF_DOCUMENT"/>
            <xs:enumeration value="SALES_DOCUMENT"/>
            <xs:enumeration value="SESSION_ACCEPTANCE"/>
            <xs:enumeration value="INTERNAL_TRANSFER"/>
            <xs:enumeration value="OUTGOING_INVOICE"/>
            <xs:enumeration value="RETURNED_INVOICE"/>
            <xs:enumeration value="PRODUCTION_DOCUMENT"/>
            <xs:enumeration value="TRANSFORMATION_DOCUMENT"/>
            <xs:enumeration value="PRODUCTION_ORDER"/>
            <xs:enumeration value="CONSOLIDATED_ORDER"/>
            <xs:enumeration value="PREPARED_REGISTER"/>
            <xs:enumeration value="MENU_CHANGE"/>
            <xs:enumeration value="PRODUCT_REPLACEMENT"/>
            <xs:enumeration value="SALES_RETURN_DOCUMENT"/>
            <xs:enumeration value="DISASSEMBLE_DOCUMENT"/>
            <xs:enumeration value="FUEL_ACCEPTANCE"/>
            <xs:enumeration value="FUEL_GAGING_DOCUMENT"/>
            <xs:enumeration value="PAYROLL"/>
        </xs:restriction>
    </xs:simpleType>
</xs:schema>