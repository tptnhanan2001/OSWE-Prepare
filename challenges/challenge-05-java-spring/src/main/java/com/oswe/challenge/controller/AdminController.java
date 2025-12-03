package com.oswe.challenge.controller;

import org.springframework.web.bind.annotation.*;
import java.io.IOException;
import java.nio.file.Files;
import java.nio.file.Paths;

@RestController
@RequestMapping("/api/admin")
public class AdminController {
    
    @GetMapping("/config")
    public String getConfig(@RequestParam(required = false) String file) {
        // VULNERABILITY: Path Traversal
        // No validation of file path
        try {
            String path = file != null ? file : "application.properties";
            // VULNERABILITY: Allows path traversal
            return new String(Files.readAllBytes(Paths.get(path)));
        } catch (IOException e) {
            return "Error: " + e.getMessage();
        }
    }
    
    @GetMapping("/flag")
    public String getFlag() {
        return "OSWE{Java_Deserialization_SQLi_XXE_PathTraversal_Chain!}";
    }
}

