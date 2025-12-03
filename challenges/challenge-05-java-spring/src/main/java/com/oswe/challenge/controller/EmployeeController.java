package com.oswe.challenge.controller;

import com.oswe.challenge.model.Employee;
import com.oswe.challenge.repository.EmployeeRepository;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.web.bind.annotation.*;

import java.util.List;

@RestController
@RequestMapping("/api/employees")
public class EmployeeController {
    
    @Autowired
    private EmployeeRepository employeeRepository;
    
    @GetMapping
    public List<Employee> getEmployees(@RequestParam(required = false) String search) {
        // VULNERABILITY: SQL Injection via Hibernate HQL
        // Direct string concatenation in query
        if (search != null && !search.isEmpty()) {
            return employeeRepository.findBySearch(search);
        }
        return employeeRepository.findAll();
    }
    
    @GetMapping("/{id}")
    public Employee getEmployee(@PathVariable Long id) {
        return employeeRepository.findById(id).orElse(null);
    }
}

