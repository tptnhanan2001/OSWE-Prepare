package com.oswe.challenge.controller;

import com.oswe.challenge.model.User;
import com.oswe.challenge.repository.UserRepository;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.web.bind.annotation.*;

import java.util.Base64;
import java.io.ByteArrayInputStream;
import java.io.ObjectInputStream;

@RestController
@RequestMapping("/api/auth")
public class AuthController {
    
    @Autowired
    private UserRepository userRepository;
    
    @PostMapping("/login")
    public String login(@RequestParam String username, @RequestParam String password) {
        User user = userRepository.findByUsernameAndPassword(username, password);
        if (user != null) {
            // Generate simple token (insecure, for demo)
            return "token_" + user.getId() + "_" + user.getRole();
        }
        return "Invalid credentials";
    }
    
    @PostMapping("/deserialize")
    public String deserialize(@RequestBody String data) {
        // VULNERABILITY: Java Deserialization
        // Deserializes user-controlled data
        try {
            byte[] bytes = Base64.getDecoder().decode(data);
            ObjectInputStream ois = new ObjectInputStream(new ByteArrayInputStream(bytes));
            Object obj = ois.readObject();
            ois.close();
            return "Deserialized: " + obj.getClass().getName();
        } catch (Exception e) {
            return "Error: " + e.getMessage();
        }
    }
}

