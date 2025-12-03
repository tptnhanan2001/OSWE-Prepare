package com.oswe.challenge.repository;

import com.oswe.challenge.model.Employee;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.data.jpa.repository.Query;
import org.springframework.data.repository.query.Param;
import java.util.List;

public interface EmployeeRepository extends JpaRepository<Employee, Long> {
    // VULNERABILITY: SQL Injection
    // Uses string concatenation instead of parameterized query
    @Query(value = "SELECT * FROM employees WHERE name LIKE '%" + "#{search}" + "%' OR department LIKE '%" + "#{search}" + "%'", nativeQuery = true)
    List<Employee> findBySearch(@Param("search") String search);
}

