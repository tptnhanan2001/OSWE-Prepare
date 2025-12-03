package com.oswe.challenge.controller;

import org.springframework.web.bind.annotation.*;
import org.w3c.dom.Document;
import org.xml.sax.InputSource;

import javax.xml.parsers.DocumentBuilder;
import javax.xml.parsers.DocumentBuilderFactory;
import java.io.StringReader;

@RestController
@RequestMapping("/api/reports")
public class ReportController {
    
    @PostMapping
    public String createReport(@RequestBody String xml) {
        // VULNERABILITY: XXE
        // XML parsing without disabling external entities
        try {
            DocumentBuilderFactory factory = DocumentBuilderFactory.newInstance();
            DocumentBuilder builder = factory.newDocumentBuilder();
            Document doc = builder.parse(new InputSource(new StringReader(xml)));
            return "Report created: " + doc.getDocumentElement().getNodeName();
        } catch (Exception e) {
            return "Error: " + e.getMessage();
        }
    }
}

