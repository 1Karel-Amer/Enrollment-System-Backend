<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subject;

// OVERHAUL: Real subject names for all 20 programs.
// Each program has 8-10 subjects per semester, across all year levels.
// program column uses the program CODE (e.g. 'BSIT') — consistent throughout.

class SubjectSeeder extends Seeder
{
    public function run(): void
    {
        $curriculum = $this->getCurriculum();

        foreach ($curriculum as $programCode => $years) {
            foreach ($years as $yearLevel => $terms) {
                foreach ($terms as $term => $subjects) {
                    foreach ($subjects as $subject) {
                        Subject::updateOrCreate(
                            ['code' => $subject['code']],
                            [
                                'title'       => $subject['title'],
                                'units'       => $subject['units'],
                                'year'        => $yearLevel,
                                'term'        => $term,
                                'program'     => $programCode,
                                'preReq'      => $subject['preReq'] ?? null,
                                'description' => $subject['description'] ?? null,
                            ]
                        );
                    }
                }
            }
        }
    }

    private function getCurriculum(): array
    {
        return [

            // ─────────────────────────────────────────────────────────────────
            // BSIT — BS in Information Technology
            // ─────────────────────────────────────────────────────────────────
            'BSIT' => [
                '1st Year' => [
                    '1st Semester' => [
                        ['code' => 'BSIT-101', 'title' => 'Introduction to Computing', 'units' => 3, 'description' => 'Overview of computer hardware, software, and basic computing concepts.'],
                        ['code' => 'BSIT-102', 'title' => 'Computer Programming 1', 'units' => 3, 'description' => 'Fundamentals of programming using Python.'],
                        ['code' => 'BSIT-103', 'title' => 'Mathematics in the Modern World', 'units' => 3, 'description' => 'Applications of mathematics in real-world scenarios.'],
                        ['code' => 'BSIT-104', 'title' => 'Purposive Communication', 'units' => 3, 'description' => 'Communication skills for academic and professional settings.'],
                        ['code' => 'BSIT-105', 'title' => 'Understanding the Self', 'units' => 3, 'description' => 'Personal development and self-awareness.'],
                        ['code' => 'BSIT-106', 'title' => 'Physical Education 1', 'units' => 2, 'description' => 'Physical fitness and wellness fundamentals.'],
                        ['code' => 'BSIT-107', 'title' => 'National Service Training Program 1', 'units' => 3, 'description' => 'Civic welfare and community service.'],
                    ],
                    '2nd Semester' => [
                        ['code' => 'BSIT-111', 'title' => 'Computer Programming 2', 'units' => 3, 'preReq' => 'BSIT-102', 'description' => 'Object-oriented programming concepts using Java.'],
                        ['code' => 'BSIT-112', 'title' => 'Digital Logic Design', 'units' => 3, 'description' => 'Boolean algebra, logic gates, and digital circuits.'],
                        ['code' => 'BSIT-113', 'title' => 'Discrete Mathematics', 'units' => 3, 'description' => 'Sets, logic, relations, and graph theory.'],
                        ['code' => 'BSIT-114', 'title' => 'Readings in Philippine History', 'units' => 3, 'description' => 'Critical study of Philippine history through primary sources.'],
                        ['code' => 'BSIT-115', 'title' => 'The Contemporary World', 'units' => 3, 'description' => 'Globalization and its effects on society.'],
                        ['code' => 'BSIT-116', 'title' => 'Physical Education 2', 'units' => 2, 'description' => 'Team sports and recreational activities.'],
                        ['code' => 'BSIT-117', 'title' => 'National Service Training Program 2', 'units' => 3, 'description' => 'Community engagement and social responsibility.'],
                    ],
                ],
                '2nd Year' => [
                    '1st Semester' => [
                        ['code' => 'BSIT-201', 'title' => 'Data Structures and Algorithms', 'units' => 3, 'preReq' => 'BSIT-111', 'description' => 'Arrays, linked lists, trees, and sorting/searching algorithms.'],
                        ['code' => 'BSIT-202', 'title' => 'Web Development 1', 'units' => 3, 'description' => 'HTML, CSS, and JavaScript fundamentals.'],
                        ['code' => 'BSIT-203', 'title' => 'Computer Organization and Architecture', 'units' => 3, 'description' => 'CPU design, memory hierarchy, and instruction sets.'],
                        ['code' => 'BSIT-204', 'title' => 'Database Management Systems', 'units' => 3, 'description' => 'Relational databases, SQL, and normalization.'],
                        ['code' => 'BSIT-205', 'title' => 'Statistics and Probability', 'units' => 3, 'description' => 'Statistical methods and probability theory for IT.'],
                        ['code' => 'BSIT-206', 'title' => 'Ethics in IT', 'units' => 3, 'description' => 'Ethical and legal issues in information technology.'],
                    ],
                    '2nd Semester' => [
                        ['code' => 'BSIT-211', 'title' => 'Web Development 2', 'units' => 3, 'preReq' => 'BSIT-202', 'description' => 'Server-side programming and frameworks.'],
                        ['code' => 'BSIT-212', 'title' => 'Operating Systems', 'units' => 3, 'description' => 'Process management, memory, and file systems.'],
                        ['code' => 'BSIT-213', 'title' => 'Object-Oriented Analysis and Design', 'units' => 3, 'description' => 'UML diagrams and OOP design patterns.'],
                        ['code' => 'BSIT-214', 'title' => 'Networking Fundamentals', 'units' => 3, 'description' => 'OSI model, TCP/IP, and network hardware.'],
                        ['code' => 'BSIT-215', 'title' => 'Human-Computer Interaction', 'units' => 3, 'description' => 'UI/UX design principles and usability.'],
                        ['code' => 'BSIT-216', 'title' => 'Technical Writing', 'units' => 3, 'description' => 'Writing technical documentation and reports.'],
                    ],
                ],
                '3rd Year' => [
                    '1st Semester' => [
                        ['code' => 'BSIT-301', 'title' => 'Software Engineering', 'units' => 3, 'description' => 'Software development lifecycle, Agile, and project management.'],
                        ['code' => 'BSIT-302', 'title' => 'Mobile Application Development', 'units' => 3, 'preReq' => 'BSIT-211', 'description' => 'Building Android and iOS applications.'],
                        ['code' => 'BSIT-303', 'title' => 'Information Security', 'units' => 3, 'description' => 'Cybersecurity principles, encryption, and threat management.'],
                        ['code' => 'BSIT-304', 'title' => 'Systems Analysis and Design', 'units' => 3, 'description' => 'Requirements gathering and system design methodologies.'],
                        ['code' => 'BSIT-305', 'title' => 'Advanced Database Systems', 'units' => 3, 'preReq' => 'BSIT-204', 'description' => 'NoSQL, distributed databases, and query optimization.'],
                        ['code' => 'BSIT-306', 'title' => 'IT Infrastructure and Cloud', 'units' => 3, 'description' => 'Cloud computing, virtualization, and IT infrastructure management.'],
                    ],
                    '2nd Semester' => [
                        ['code' => 'BSIT-311', 'title' => 'Network Administration', 'units' => 3, 'preReq' => 'BSIT-214', 'description' => 'Network configuration, monitoring, and troubleshooting.'],
                        ['code' => 'BSIT-312', 'title' => 'Capstone Project 1', 'units' => 3, 'description' => 'Project proposal and initial system design.'],
                        ['code' => 'BSIT-313', 'title' => 'Enterprise Resource Planning', 'units' => 3, 'description' => 'ERP systems and business process integration.'],
                        ['code' => 'BSIT-314', 'title' => 'IT Project Management', 'units' => 3, 'description' => 'Planning, executing, and closing IT projects.'],
                        ['code' => 'BSIT-315', 'title' => 'Technopreneurship', 'units' => 3, 'description' => 'Technology-based entrepreneurship and startup development.'],
                        ['code' => 'BSIT-316', 'title' => 'Practicum Preparation', 'units' => 1, 'description' => 'Professional readiness and OJT orientation.'],
                    ],
                ],
                '4th Year' => [
                    '1st Semester' => [
                        ['code' => 'BSIT-401', 'title' => 'Practicum / OJT', 'units' => 6, 'description' => '486 hours of on-the-job training in an IT organization.'],
                        ['code' => 'BSIT-402', 'title' => 'Capstone Project 2', 'units' => 3, 'preReq' => 'BSIT-312', 'description' => 'System implementation, testing, and deployment.'],
                        ['code' => 'BSIT-403', 'title' => 'Emerging Technologies', 'units' => 3, 'description' => 'AI, IoT, blockchain, and future tech trends.'],
                    ],
                    '2nd Semester' => [
                        ['code' => 'BSIT-411', 'title' => 'Capstone Project 3', 'units' => 3, 'preReq' => 'BSIT-402', 'description' => 'Final defense, documentation, and project delivery.'],
                        ['code' => 'BSIT-412', 'title' => 'IT Governance and Compliance', 'units' => 3, 'description' => 'IT frameworks, COBIT, and regulatory compliance.'],
                        ['code' => 'BSIT-413', 'title' => 'Social Issues and Professional Practice', 'units' => 3, 'description' => 'IT profession, intellectual property, and social responsibility.'],
                    ],
                ],
            ],

            // ─────────────────────────────────────────────────────────────────
            // BSCS — BS in Computer Science
            // ─────────────────────────────────────────────────────────────────
            'BSCS' => [
                '1st Year' => [
                    '1st Semester' => [
                        ['code' => 'BSCS-101', 'title' => 'Introduction to Computer Science', 'units' => 3, 'description' => 'History of computing and problem-solving fundamentals.'],
                        ['code' => 'BSCS-102', 'title' => 'Programming Fundamentals', 'units' => 3, 'description' => 'Algorithmic thinking and programming using C++.'],
                        ['code' => 'BSCS-103', 'title' => 'Calculus 1', 'units' => 3, 'description' => 'Limits, derivatives, and their applications.'],
                        ['code' => 'BSCS-104', 'title' => 'Purposive Communication', 'units' => 3, 'description' => 'Professional and academic communication.'],
                        ['code' => 'BSCS-105', 'title' => 'Understanding the Self', 'units' => 3, 'description' => 'Personal identity and self-development.'],
                        ['code' => 'BSCS-106', 'title' => 'Physical Education 1', 'units' => 2, 'description' => 'Fitness and wellness.'],
                        ['code' => 'BSCS-107', 'title' => 'NSTP 1', 'units' => 3, 'description' => 'National service training.'],
                    ],
                    '2nd Semester' => [
                        ['code' => 'BSCS-111', 'title' => 'Object-Oriented Programming', 'units' => 3, 'preReq' => 'BSCS-102', 'description' => 'OOP principles using Java.'],
                        ['code' => 'BSCS-112', 'title' => 'Calculus 2', 'units' => 3, 'preReq' => 'BSCS-103', 'description' => 'Integration and its applications.'],
                        ['code' => 'BSCS-113', 'title' => 'Discrete Mathematics', 'units' => 3, 'description' => 'Logic, sets, graphs, and combinatorics.'],
                        ['code' => 'BSCS-114', 'title' => 'Digital Logic Design', 'units' => 3, 'description' => 'Boolean algebra and digital circuit fundamentals.'],
                        ['code' => 'BSCS-115', 'title' => 'Readings in Philippine History', 'units' => 3, 'description' => 'Philippine history through primary sources.'],
                        ['code' => 'BSCS-116', 'title' => 'Physical Education 2', 'units' => 2, 'description' => 'Sports and team activities.'],
                        ['code' => 'BSCS-117', 'title' => 'NSTP 2', 'units' => 3, 'description' => 'Community service.'],
                    ],
                ],
                '2nd Year' => [
                    '1st Semester' => [
                        ['code' => 'BSCS-201', 'title' => 'Data Structures', 'units' => 3, 'preReq' => 'BSCS-111', 'description' => 'Linear and non-linear data structures and their operations.'],
                        ['code' => 'BSCS-202', 'title' => 'Linear Algebra', 'units' => 3, 'description' => 'Vectors, matrices, and linear transformations.'],
                        ['code' => 'BSCS-203', 'title' => 'Computer Organization', 'units' => 3, 'description' => 'CPU structure, instruction sets, and memory systems.'],
                        ['code' => 'BSCS-204', 'title' => 'Database Systems', 'units' => 3, 'description' => 'Relational database design and SQL.'],
                        ['code' => 'BSCS-205', 'title' => 'Probability and Statistics', 'units' => 3, 'description' => 'Statistical analysis for computer science applications.'],
                        ['code' => 'BSCS-206', 'title' => 'Ethics in CS', 'units' => 3, 'description' => 'Ethical considerations in computing.'],
                    ],
                    '2nd Semester' => [
                        ['code' => 'BSCS-211', 'title' => 'Algorithms and Complexity', 'units' => 3, 'preReq' => 'BSCS-201', 'description' => 'Algorithm design paradigms and complexity analysis.'],
                        ['code' => 'BSCS-212', 'title' => 'Operating Systems', 'units' => 3, 'description' => 'Processes, scheduling, memory management.'],
                        ['code' => 'BSCS-213', 'title' => 'Software Engineering', 'units' => 3, 'description' => 'Software development methodologies and tools.'],
                        ['code' => 'BSCS-214', 'title' => 'Computer Networks', 'units' => 3, 'description' => 'Network protocols, architecture, and security.'],
                        ['code' => 'BSCS-215', 'title' => 'Numerical Methods', 'units' => 3, 'description' => 'Computational solutions to mathematical problems.'],
                    ],
                ],
                '3rd Year' => [
                    '1st Semester' => [
                        ['code' => 'BSCS-301', 'title' => 'Artificial Intelligence', 'units' => 3, 'description' => 'Search algorithms, knowledge representation, and AI fundamentals.'],
                        ['code' => 'BSCS-302', 'title' => 'Programming Languages', 'units' => 3, 'description' => 'Language paradigms, syntax, and semantics.'],
                        ['code' => 'BSCS-303', 'title' => 'Theory of Automata', 'units' => 3, 'description' => 'Finite automata, grammars, and Turing machines.'],
                        ['code' => 'BSCS-304', 'title' => 'Computer Graphics', 'units' => 3, 'description' => 'Rendering, transformations, and graphics programming.'],
                        ['code' => 'BSCS-305', 'title' => 'Compiler Design', 'units' => 3, 'description' => 'Lexical analysis, parsing, and code generation.'],
                    ],
                    '2nd Semester' => [
                        ['code' => 'BSCS-311', 'title' => 'Machine Learning', 'units' => 3, 'preReq' => 'BSCS-301', 'description' => 'Supervised and unsupervised learning algorithms.'],
                        ['code' => 'BSCS-312', 'title' => 'Distributed Systems', 'units' => 3, 'description' => 'Distributed computing, consistency, and fault tolerance.'],
                        ['code' => 'BSCS-313', 'title' => 'Information Security', 'units' => 3, 'description' => 'Cryptography, network security, and threat modeling.'],
                        ['code' => 'BSCS-314', 'title' => 'Thesis Writing 1', 'units' => 3, 'description' => 'Research proposal and related literature review.'],
                        ['code' => 'BSCS-315', 'title' => 'Technopreneurship', 'units' => 3, 'description' => 'Tech startup ideation and business planning.'],
                    ],
                ],
                '4th Year' => [
                    '1st Semester' => [
                        ['code' => 'BSCS-401', 'title' => 'Thesis Writing 2', 'units' => 3, 'preReq' => 'BSCS-314', 'description' => 'System implementation and data collection.'],
                        ['code' => 'BSCS-402', 'title' => 'Deep Learning', 'units' => 3, 'preReq' => 'BSCS-311', 'description' => 'Neural networks, CNNs, and RNNs.'],
                        ['code' => 'BSCS-403', 'title' => 'Parallel Computing', 'units' => 3, 'description' => 'Multi-core and GPU programming.'],
                        ['code' => 'BSCS-404', 'title' => 'Practicum', 'units' => 6, 'description' => 'Industry immersion and applied research.'],
                    ],
                    '2nd Semester' => [
                        ['code' => 'BSCS-411', 'title' => 'Thesis Writing 3', 'units' => 3, 'preReq' => 'BSCS-401', 'description' => 'Final thesis defense and documentation.'],
                        ['code' => 'BSCS-412', 'title' => 'Social and Professional Issues', 'units' => 3, 'description' => 'Computing profession, ethics, and law.'],
                        ['code' => 'BSCS-413', 'title' => 'Emerging Trends in CS', 'units' => 3, 'description' => 'Quantum computing, edge computing, and future trends.'],
                    ],
                ],
            ],

            // ─────────────────────────────────────────────────────────────────
            // BSDS — BS in Data Science
            // ─────────────────────────────────────────────────────────────────
            'BSDS' => [
                '1st Year' => [
                    '1st Semester' => [
                        ['code' => 'BSDS-101', 'title' => 'Introduction to Data Science', 'units' => 3, 'description' => 'Overview of data science tools, workflow, and applications.'],
                        ['code' => 'BSDS-102', 'title' => 'Programming for Data Science', 'units' => 3, 'description' => 'Python programming for data manipulation.'],
                        ['code' => 'BSDS-103', 'title' => 'Calculus for Data Science', 'units' => 3, 'description' => 'Differential and integral calculus applications.'],
                        ['code' => 'BSDS-104', 'title' => 'Purposive Communication', 'units' => 3, 'description' => 'Academic and professional communication.'],
                        ['code' => 'BSDS-105', 'title' => 'Understanding the Self', 'units' => 3, 'description' => 'Self-awareness and development.'],
                        ['code' => 'BSDS-106', 'title' => 'Physical Education 1', 'units' => 2, 'description' => 'Physical fitness fundamentals.'],
                    ],
                    '2nd Semester' => [
                        ['code' => 'BSDS-111', 'title' => 'Statistics and Probability', 'units' => 3, 'description' => 'Descriptive and inferential statistics.'],
                        ['code' => 'BSDS-112', 'title' => 'Linear Algebra for Data Science', 'units' => 3, 'description' => 'Matrix operations and eigenvalues.'],
                        ['code' => 'BSDS-113', 'title' => 'Database Fundamentals', 'units' => 3, 'description' => 'SQL and relational database concepts.'],
                        ['code' => 'BSDS-114', 'title' => 'Data Visualization', 'units' => 3, 'description' => 'Charts, dashboards, and storytelling with data.'],
                        ['code' => 'BSDS-115', 'title' => 'Readings in Philippine History', 'units' => 3, 'description' => 'Philippine history through primary sources.'],
                        ['code' => 'BSDS-116', 'title' => 'Physical Education 2', 'units' => 2, 'description' => 'Sports and wellness.'],
                    ],
                ],
                '2nd Year' => [
                    '1st Semester' => [
                        ['code' => 'BSDS-201', 'title' => 'Data Wrangling', 'units' => 3, 'description' => 'Cleaning, transforming, and preparing data.'],
                        ['code' => 'BSDS-202', 'title' => 'Machine Learning Fundamentals', 'units' => 3, 'description' => 'Supervised learning and model evaluation.'],
                        ['code' => 'BSDS-203', 'title' => 'Big Data Technologies', 'units' => 3, 'description' => 'Hadoop, Spark, and distributed data processing.'],
                        ['code' => 'BSDS-204', 'title' => 'Inferential Statistics', 'units' => 3, 'description' => 'Hypothesis testing and regression analysis.'],
                        ['code' => 'BSDS-205', 'title' => 'Ethics in Data Science', 'units' => 3, 'description' => 'Data privacy, bias, and responsible AI.'],
                    ],
                    '2nd Semester' => [
                        ['code' => 'BSDS-211', 'title' => 'Advanced Machine Learning', 'units' => 3, 'preReq' => 'BSDS-202', 'description' => 'Ensemble methods, SVMs, and unsupervised learning.'],
                        ['code' => 'BSDS-212', 'title' => 'Natural Language Processing', 'units' => 3, 'description' => 'Text mining, sentiment analysis, and NLP pipelines.'],
                        ['code' => 'BSDS-213', 'title' => 'Data Engineering', 'units' => 3, 'description' => 'ETL pipelines and data warehouse design.'],
                        ['code' => 'BSDS-214', 'title' => 'Time Series Analysis', 'units' => 3, 'description' => 'Forecasting and temporal data modeling.'],
                        ['code' => 'BSDS-215', 'title' => 'Research Methods', 'units' => 3, 'description' => 'Scientific research design for data science.'],
                    ],
                ],
                '3rd Year' => [
                    '1st Semester' => [
                        ['code' => 'BSDS-301', 'title' => 'Deep Learning', 'units' => 3, 'preReq' => 'BSDS-211', 'description' => 'Neural networks and deep learning architectures.'],
                        ['code' => 'BSDS-302', 'title' => 'Computer Vision', 'units' => 3, 'description' => 'Image recognition and convolutional neural networks.'],
                        ['code' => 'BSDS-303', 'title' => 'Cloud Computing for Data', 'units' => 3, 'description' => 'AWS, GCP, and Azure for data workloads.'],
                        ['code' => 'BSDS-304', 'title' => 'Capstone Research 1', 'units' => 3, 'description' => 'Data science research proposal and dataset preparation.'],
                        ['code' => 'BSDS-305', 'title' => 'Business Analytics', 'units' => 3, 'description' => 'Data-driven decision making for business.'],
                    ],
                    '2nd Semester' => [
                        ['code' => 'BSDS-311', 'title' => 'Capstone Research 2', 'units' => 3, 'preReq' => 'BSDS-304', 'description' => 'Model development and analysis.'],
                        ['code' => 'BSDS-312', 'title' => 'AI Ethics and Governance', 'units' => 3, 'description' => 'Responsible deployment of AI systems.'],
                        ['code' => 'BSDS-313', 'title' => 'Technopreneurship', 'units' => 3, 'description' => 'Data-driven startup ideation.'],
                        ['code' => 'BSDS-314', 'title' => 'Practicum Preparation', 'units' => 1, 'description' => 'OJT readiness and portfolio development.'],
                    ],
                ],
                '4th Year' => [
                    '1st Semester' => [
                        ['code' => 'BSDS-401', 'title' => 'Practicum / OJT', 'units' => 6, 'description' => 'Industry exposure in a data science role.'],
                        ['code' => 'BSDS-402', 'title' => 'Capstone Research 3', 'units' => 3, 'preReq' => 'BSDS-311', 'description' => 'Final system deployment and results documentation.'],
                        ['code' => 'BSDS-403', 'title' => 'Emerging Trends in AI', 'units' => 3, 'description' => 'Generative AI, LLMs, and future directions.'],
                    ],
                    '2nd Semester' => [
                        ['code' => 'BSDS-411', 'title' => 'Capstone Research Defense', 'units' => 3, 'preReq' => 'BSDS-402', 'description' => 'Final oral defense and publication readiness.'],
                        ['code' => 'BSDS-412', 'title' => 'Professional Practice', 'units' => 3, 'description' => 'Data science profession, IP, and industry standards.'],
                    ],
                ],
            ],

            // ─────────────────────────────────────────────────────────────────
            // BSA — BS in Accountancy
            // ─────────────────────────────────────────────────────────────────
            'BSA' => [
                '1st Year' => [
                    '1st Semester' => [
                        ['code' => 'BSA-101', 'title' => 'Fundamentals of Accounting 1', 'units' => 6, 'description' => 'Basic accounting concepts and the accounting cycle.'],
                        ['code' => 'BSA-102', 'title' => 'Business Mathematics', 'units' => 3, 'description' => 'Mathematical applications in business.'],
                        ['code' => 'BSA-103', 'title' => 'Purposive Communication', 'units' => 3, 'description' => 'Business communication and report writing.'],
                        ['code' => 'BSA-104', 'title' => 'Understanding the Self', 'units' => 3, 'description' => 'Self-awareness and personal development.'],
                        ['code' => 'BSA-105', 'title' => 'Physical Education 1', 'units' => 2, 'description' => 'Physical fitness.'],
                        ['code' => 'BSA-106', 'title' => 'NSTP 1', 'units' => 3, 'description' => 'National service training.'],
                    ],
                    '2nd Semester' => [
                        ['code' => 'BSA-111', 'title' => 'Fundamentals of Accounting 2', 'units' => 6, 'preReq' => 'BSA-101', 'description' => 'Continuation of accounting cycle: adjustments and financial statements.'],
                        ['code' => 'BSA-112', 'title' => 'Business Law', 'units' => 3, 'description' => 'Legal environment of business.'],
                        ['code' => 'BSA-113', 'title' => 'Microeconomics', 'units' => 3, 'description' => 'Supply, demand, and market structures.'],
                        ['code' => 'BSA-114', 'title' => 'Readings in Philippine History', 'units' => 3, 'description' => 'Historical context of Philippine society.'],
                        ['code' => 'BSA-115', 'title' => 'Physical Education 2', 'units' => 2, 'description' => 'Recreational sports.'],
                        ['code' => 'BSA-116', 'title' => 'NSTP 2', 'units' => 3, 'description' => 'Community service.'],
                    ],
                ],
                '2nd Year' => [
                    '1st Semester' => [
                        ['code' => 'BSA-201', 'title' => 'Financial Accounting and Reporting 1', 'units' => 6, 'preReq' => 'BSA-111', 'description' => 'PFRS application to financial statement preparation.'],
                        ['code' => 'BSA-202', 'title' => 'Cost Accounting and Control', 'units' => 6, 'description' => 'Job order and process costing systems.'],
                        ['code' => 'BSA-203', 'title' => 'Macroeconomics', 'units' => 3, 'description' => 'National income, inflation, and fiscal policy.'],
                        ['code' => 'BSA-204', 'title' => 'Business Statistics', 'units' => 3, 'description' => 'Statistical tools for business decision making.'],
                    ],
                    '2nd Semester' => [
                        ['code' => 'BSA-211', 'title' => 'Financial Accounting and Reporting 2', 'units' => 6, 'preReq' => 'BSA-201', 'description' => 'Complex PFRS topics: leases, consolidation, and investments.'],
                        ['code' => 'BSA-212', 'title' => 'Management Accounting', 'units' => 6, 'description' => 'Budgeting, variance analysis, and performance measurement.'],
                        ['code' => 'BSA-213', 'title' => 'Business Finance', 'units' => 3, 'description' => 'Capital structure, valuation, and investment decisions.'],
                        ['code' => 'BSA-214', 'title' => 'Computer Applications in Accounting', 'units' => 3, 'description' => 'Accounting software and spreadsheet tools.'],
                    ],
                ],
                '3rd Year' => [
                    '1st Semester' => [
                        ['code' => 'BSA-301', 'title' => 'Auditing Theory', 'units' => 6, 'description' => 'Audit principles, standards, and procedures.'],
                        ['code' => 'BSA-302', 'title' => 'Taxation 1 — Income Tax', 'units' => 6, 'description' => 'Philippine income tax laws and computation.'],
                        ['code' => 'BSA-303', 'title' => 'Financial Accounting and Reporting 3', 'units' => 6, 'preReq' => 'BSA-211', 'description' => 'Advanced consolidation and specialized industries.'],
                    ],
                    '2nd Semester' => [
                        ['code' => 'BSA-311', 'title' => 'Auditing and Assurance Services', 'units' => 6, 'preReq' => 'BSA-301', 'description' => 'Substantive testing, audit reports, and quality control.'],
                        ['code' => 'BSA-312', 'title' => 'Taxation 2 — Business Tax', 'units' => 6, 'preReq' => 'BSA-302', 'description' => 'VAT, percentage taxes, and transfer taxes.'],
                        ['code' => 'BSA-313', 'title' => 'Regulatory Framework', 'units' => 3, 'description' => 'SEC, BSP, and BIR compliance for businesses.'],
                    ],
                ],
                '4th Year' => [
                    '1st Semester' => [
                        ['code' => 'BSA-401', 'title' => 'Accounting Information Systems', 'units' => 6, 'description' => 'IT controls and automated accounting systems.'],
                        ['code' => 'BSA-402', 'title' => 'Advanced Financial Accounting', 'units' => 6, 'preReq' => 'BSA-303', 'description' => 'Partnership accounting and government accounting.'],
                        ['code' => 'BSA-403', 'title' => 'CPA Review — FAR', 'units' => 3, 'description' => 'Comprehensive review of financial accounting topics.'],
                    ],
                    '2nd Semester' => [
                        ['code' => 'BSA-411', 'title' => 'CPA Review — AFAR', 'units' => 3, 'description' => 'Advanced financial accounting and reporting review.'],
                        ['code' => 'BSA-412', 'title' => 'CPA Review — Taxation', 'units' => 3, 'description' => 'Comprehensive tax review for licensure exam.'],
                        ['code' => 'BSA-413', 'title' => 'CPA Review — MAS', 'units' => 3, 'description' => 'Management advisory services review.'],
                        ['code' => 'BSA-414', 'title' => 'Practicum', 'units' => 6, 'description' => 'Accounting firm or corporate finance OJT.'],
                    ],
                ],
            ],

            // ─────────────────────────────────────────────────────────────────
            // BSCrim — BS in Criminology
            // ─────────────────────────────────────────────────────────────────
            'BSCrim' => [
                '1st Year' => [
                    '1st Semester' => [
                        ['code' => 'BSCrim-101', 'title' => 'Introduction to Criminology', 'units' => 3, 'description' => 'Nature, scope, and history of criminology.'],
                        ['code' => 'BSCrim-102', 'title' => 'Criminal Law Book 1', 'units' => 3, 'description' => 'Revised Penal Code: principles and felonies.'],
                        ['code' => 'BSCrim-103', 'title' => 'Purposive Communication', 'units' => 3, 'description' => 'Professional communication in criminal justice.'],
                        ['code' => 'BSCrim-104', 'title' => 'Understanding the Self', 'units' => 3, 'description' => 'Self-development for law enforcement professionals.'],
                        ['code' => 'BSCrim-105', 'title' => 'Physical Education 1', 'units' => 2, 'description' => 'Physical fitness and endurance.'],
                        ['code' => 'BSCrim-106', 'title' => 'NSTP 1', 'units' => 3, 'description' => 'National service training.'],
                    ],
                    '2nd Semester' => [
                        ['code' => 'BSCrim-111', 'title' => 'Criminal Law Book 2', 'units' => 3, 'preReq' => 'BSCrim-102', 'description' => 'Specific crimes under the Revised Penal Code.'],
                        ['code' => 'BSCrim-112', 'title' => 'Law Enforcement Administration', 'units' => 3, 'description' => 'Police organization and management.'],
                        ['code' => 'BSCrim-113', 'title' => 'Crime Detection and Investigation', 'units' => 3, 'description' => 'Principles and techniques of criminal investigation.'],
                        ['code' => 'BSCrim-114', 'title' => 'Readings in Philippine History', 'units' => 3, 'description' => 'Philippine history context.'],
                        ['code' => 'BSCrim-115', 'title' => 'Physical Education 2', 'units' => 2, 'description' => 'Self-defense and martial arts basics.'],
                        ['code' => 'BSCrim-116', 'title' => 'NSTP 2', 'units' => 3, 'description' => 'Community service.'],
                    ],
                ],
                '2nd Year' => [
                    '1st Semester' => [
                        ['code' => 'BSCrim-201', 'title' => 'Criminalistics 1', 'units' => 3, 'description' => 'Forensic science and physical evidence examination.'],
                        ['code' => 'BSCrim-202', 'title' => 'Criminal Sociology', 'units' => 3, 'description' => 'Social factors influencing criminal behavior.'],
                        ['code' => 'BSCrim-203', 'title' => 'Juvenile Delinquency', 'units' => 3, 'description' => 'Causes and treatment of youth offending.'],
                        ['code' => 'BSCrim-204', 'title' => 'Legal Medicine', 'units' => 3, 'description' => 'Medical evidence in legal proceedings.'],
                        ['code' => 'BSCrim-205', 'title' => 'Special Crime Investigation', 'units' => 3, 'description' => 'Investigation of heinous and organized crimes.'],
                    ],
                    '2nd Semester' => [
                        ['code' => 'BSCrim-211', 'title' => 'Criminalistics 2', 'units' => 3, 'preReq' => 'BSCrim-201', 'description' => 'Ballistics, questioned documents, and fingerprinting.'],
                        ['code' => 'BSCrim-212', 'title' => 'Correctional Administration', 'units' => 3, 'description' => 'Prison management and rehabilitation programs.'],
                        ['code' => 'BSCrim-213', 'title' => 'Traffic Management', 'units' => 3, 'description' => 'Traffic laws, enforcement, and accident investigation.'],
                        ['code' => 'BSCrim-214', 'title' => 'Criminal Psychology', 'units' => 3, 'description' => 'Psychological profiling and offender behavior.'],
                        ['code' => 'BSCrim-215', 'title' => 'Drug Education and Vice Control', 'units' => 3, 'description' => 'Drug laws, addiction, and anti-vice operations.'],
                    ],
                ],
                '3rd Year' => [
                    '1st Semester' => [
                        ['code' => 'BSCrim-301', 'title' => 'Police Photography', 'units' => 3, 'description' => 'Crime scene photography and documentation.'],
                        ['code' => 'BSCrim-302', 'title' => 'Cybercrime Investigation', 'units' => 3, 'description' => 'Digital evidence and online criminal activity.'],
                        ['code' => 'BSCrim-303', 'title' => 'Public Safety Administration', 'units' => 3, 'description' => 'Disaster response and community policing.'],
                        ['code' => 'BSCrim-304', 'title' => 'Human Rights and International Law', 'units' => 3, 'description' => 'International criminal law and human rights frameworks.'],
                        ['code' => 'BSCrim-305', 'title' => 'Research Methods in Criminology', 'units' => 3, 'description' => 'Quantitative and qualitative research in criminal justice.'],
                    ],
                    '2nd Semester' => [
                        ['code' => 'BSCrim-311', 'title' => 'Practicum / Field Internship', 'units' => 6, 'description' => 'Hands-on training with law enforcement agencies.'],
                        ['code' => 'BSCrim-312', 'title' => 'Capstone Research', 'units' => 3, 'description' => 'Applied criminology research and presentation.'],
                        ['code' => 'BSCrim-313', 'title' => 'Penology and Offender Treatment', 'units' => 3, 'description' => 'Theories of punishment and rehabilitation.'],
                    ],
                ],
                '4th Year' => [
                    '1st Semester' => [
                        ['code' => 'BSCrim-401', 'title' => 'Board Exam Review 1', 'units' => 3, 'description' => 'Comprehensive review: criminal law and criminalistics.'],
                        ['code' => 'BSCrim-402', 'title' => 'Crisis Management', 'units' => 3, 'description' => 'Hostage negotiation and critical incident response.'],
                        ['code' => 'BSCrim-403', 'title' => 'Ethics in Criminal Justice', 'units' => 3, 'description' => 'Professional ethics for law enforcement officers.'],
                    ],
                    '2nd Semester' => [
                        ['code' => 'BSCrim-411', 'title' => 'Board Exam Review 2', 'units' => 3, 'description' => 'Comprehensive review: law enforcement and corrections.'],
                        ['code' => 'BSCrim-412', 'title' => 'Counter-Terrorism', 'units' => 3, 'description' => 'Terrorism frameworks and counter-terrorism operations.'],
                        ['code' => 'BSCrim-413', 'title' => 'Intelligence Operations', 'units' => 3, 'description' => 'Intelligence gathering and analysis in law enforcement.'],
                    ],
                ],
            ],

            // ─────────────────────────────────────────────────────────────────
            // BSPsych — BS in Psychology
            // ─────────────────────────────────────────────────────────────────
            'BSPsych' => [
                '1st Year' => [
                    '1st Semester' => [
                        ['code' => 'BSPsych-101', 'title' => 'General Psychology', 'units' => 3, 'description' => 'Overview of psychology as a science and profession.'],
                        ['code' => 'BSPsych-102', 'title' => 'Biological Bases of Behavior', 'units' => 3, 'description' => 'Neuroscience, brain structures, and behavior.'],
                        ['code' => 'BSPsych-103', 'title' => 'Purposive Communication', 'units' => 3, 'description' => 'Professional and academic communication.'],
                        ['code' => 'BSPsych-104', 'title' => 'Understanding the Self', 'units' => 3, 'description' => 'Identity, self-concept, and personal growth.'],
                        ['code' => 'BSPsych-105', 'title' => 'Physical Education 1', 'units' => 2, 'description' => 'Physical wellness.'],
                        ['code' => 'BSPsych-106', 'title' => 'NSTP 1', 'units' => 3, 'description' => 'National service training.'],
                    ],
                    '2nd Semester' => [
                        ['code' => 'BSPsych-111', 'title' => 'Developmental Psychology', 'units' => 3, 'description' => 'Human development across the lifespan.'],
                        ['code' => 'BSPsych-112', 'title' => 'Social Psychology', 'units' => 3, 'description' => 'Social influence, attitudes, and group dynamics.'],
                        ['code' => 'BSPsych-113', 'title' => 'Research Methods 1', 'units' => 3, 'description' => 'Quantitative research design and data collection.'],
                        ['code' => 'BSPsych-114', 'title' => 'Statistics for Psychology', 'units' => 3, 'description' => 'Descriptive and inferential statistics for behavioral data.'],
                        ['code' => 'BSPsych-115', 'title' => 'Readings in Philippine History', 'units' => 3, 'description' => 'Philippine history.'],
                        ['code' => 'BSPsych-116', 'title' => 'NSTP 2', 'units' => 3, 'description' => 'Community service.'],
                    ],
                ],
                '2nd Year' => [
                    '1st Semester' => [
                        ['code' => 'BSPsych-201', 'title' => 'Psychological Assessment 1', 'units' => 3, 'description' => 'Intelligence and aptitude testing.'],
                        ['code' => 'BSPsych-202', 'title' => 'Abnormal Psychology', 'units' => 3, 'description' => 'Classification and etiology of psychological disorders.'],
                        ['code' => 'BSPsych-203', 'title' => 'Cognitive Psychology', 'units' => 3, 'description' => 'Memory, attention, perception, and problem solving.'],
                        ['code' => 'BSPsych-204', 'title' => 'Theories of Personality', 'units' => 3, 'description' => 'Psychodynamic, humanistic, and trait theories.'],
                        ['code' => 'BSPsych-205', 'title' => 'Industrial and Organizational Psychology', 'units' => 3, 'description' => 'Work behavior, motivation, and HR applications.'],
                    ],
                    '2nd Semester' => [
                        ['code' => 'BSPsych-211', 'title' => 'Psychological Assessment 2', 'units' => 3, 'preReq' => 'BSPsych-201', 'description' => 'Personality and projective assessments.'],
                        ['code' => 'BSPsych-212', 'title' => 'Health Psychology', 'units' => 3, 'description' => 'Psychological factors in physical health.'],
                        ['code' => 'BSPsych-213', 'title' => 'Research Methods 2', 'units' => 3, 'preReq' => 'BSPsych-113', 'description' => 'Qualitative research and mixed methods.'],
                        ['code' => 'BSPsych-214', 'title' => 'Learning and Behavior', 'units' => 3, 'description' => 'Classical and operant conditioning principles.'],
                        ['code' => 'BSPsych-215', 'title' => 'Ethics in Psychology', 'units' => 3, 'description' => 'APA ethics code and professional conduct.'],
                    ],
                ],
                '3rd Year' => [
                    '1st Semester' => [
                        ['code' => 'BSPsych-301', 'title' => 'Counseling and Psychotherapy', 'units' => 3, 'description' => 'Therapeutic models and helping relationships.'],
                        ['code' => 'BSPsych-302', 'title' => 'Community Psychology', 'units' => 3, 'description' => 'Mental health in community and social contexts.'],
                        ['code' => 'BSPsych-303', 'title' => 'Neuropsychology', 'units' => 3, 'description' => 'Brain-behavior relationships and neurological assessment.'],
                        ['code' => 'BSPsych-304', 'title' => 'Child and Adolescent Psychology', 'units' => 3, 'description' => 'Developmental issues in children and teenagers.'],
                        ['code' => 'BSPsych-305', 'title' => 'Thesis Writing 1', 'units' => 3, 'description' => 'Research proposal development.'],
                    ],
                    '2nd Semester' => [
                        ['code' => 'BSPsych-311', 'title' => 'Group Dynamics and Facilitation', 'units' => 3, 'description' => 'Group processes and facilitating group work.'],
                        ['code' => 'BSPsych-312', 'title' => 'Practicum 1', 'units' => 3, 'description' => 'Supervised psychological practice in a community setting.'],
                        ['code' => 'BSPsych-313', 'title' => 'Thesis Writing 2', 'units' => 3, 'preReq' => 'BSPsych-305', 'description' => 'Data collection and analysis.'],
                        ['code' => 'BSPsych-314', 'title' => 'Gerontology', 'units' => 3, 'description' => 'Psychological aspects of aging.'],
                    ],
                ],
                '4th Year' => [
                    '1st Semester' => [
                        ['code' => 'BSPsych-401', 'title' => 'Practicum 2', 'units' => 6, 'description' => 'Advanced supervised practice in clinical or organizational settings.'],
                        ['code' => 'BSPsych-402', 'title' => 'Thesis Writing 3', 'units' => 3, 'preReq' => 'BSPsych-313', 'description' => 'Final thesis defense and documentation.'],
                        ['code' => 'BSPsych-403', 'title' => 'Forensic Psychology', 'units' => 3, 'description' => 'Psychology in the legal and criminal justice system.'],
                    ],
                    '2nd Semester' => [
                        ['code' => 'BSPsych-411', 'title' => 'Psychopathology', 'units' => 3, 'description' => 'Advanced study of mental disorders and diagnosis.'],
                        ['code' => 'BSPsych-412', 'title' => 'Professional Practice and Ethics', 'units' => 3, 'description' => 'Licensure preparation and professional standards.'],
                    ],
                ],
            ],

            // ─────────────────────────────────────────────────────────────────
            // BSCE — BS in Civil Engineering (5 Years)
            // ─────────────────────────────────────────────────────────────────
            'BSCE' => [
                '1st Year' => [
                    '1st Semester' => [
                        ['code' => 'BSCE-101', 'title' => 'Engineering Drawing', 'units' => 3, 'description' => 'Orthographic projection and technical drawing.'],
                        ['code' => 'BSCE-102', 'title' => 'Calculus 1', 'units' => 3, 'description' => 'Differential calculus.'],
                        ['code' => 'BSCE-103', 'title' => 'Chemistry for Engineers', 'units' => 3, 'description' => 'General chemistry principles for engineering.'],
                        ['code' => 'BSCE-104', 'title' => 'Purposive Communication', 'units' => 3, 'description' => 'Technical and professional communication.'],
                        ['code' => 'BSCE-105', 'title' => 'Understanding the Self', 'units' => 3, 'description' => 'Self-development.'],
                        ['code' => 'BSCE-106', 'title' => 'Physical Education 1', 'units' => 2, 'description' => 'Physical fitness.'],
                        ['code' => 'BSCE-107', 'title' => 'NSTP 1', 'units' => 3, 'description' => 'National service training.'],
                    ],
                    '2nd Semester' => [
                        ['code' => 'BSCE-111', 'title' => 'Calculus 2', 'units' => 3, 'preReq' => 'BSCE-102', 'description' => 'Integral calculus.'],
                        ['code' => 'BSCE-112', 'title' => 'Physics for Engineers 1', 'units' => 3, 'description' => 'Mechanics and thermodynamics.'],
                        ['code' => 'BSCE-113', 'title' => 'Computer Programming for CE', 'units' => 3, 'description' => 'Programming applications in civil engineering.'],
                        ['code' => 'BSCE-114', 'title' => 'Readings in Philippine History', 'units' => 3, 'description' => 'Philippine history.'],
                        ['code' => 'BSCE-115', 'title' => 'Physical Education 2', 'units' => 2, 'description' => 'Sports and fitness.'],
                        ['code' => 'BSCE-116', 'title' => 'NSTP 2', 'units' => 3, 'description' => 'Community service.'],
                    ],
                ],
                '2nd Year' => [
                    '1st Semester' => [
                        ['code' => 'BSCE-201', 'title' => 'Calculus 3', 'units' => 3, 'preReq' => 'BSCE-111', 'description' => 'Multivariable calculus.'],
                        ['code' => 'BSCE-202', 'title' => 'Physics for Engineers 2', 'units' => 3, 'description' => 'Electricity, magnetism, and optics.'],
                        ['code' => 'BSCE-203', 'title' => 'Statics of Rigid Bodies', 'units' => 3, 'description' => 'Equilibrium of forces and moments.'],
                        ['code' => 'BSCE-204', 'title' => 'Engineering Materials', 'units' => 3, 'description' => 'Properties and selection of construction materials.'],
                        ['code' => 'BSCE-205', 'title' => 'Surveying 1', 'units' => 3, 'description' => 'Distance and angle measurement in surveying.'],
                    ],
                    '2nd Semester' => [
                        ['code' => 'BSCE-211', 'title' => 'Differential Equations', 'units' => 3, 'description' => 'Ordinary and partial differential equations.'],
                        ['code' => 'BSCE-212', 'title' => 'Dynamics of Rigid Bodies', 'units' => 3, 'preReq' => 'BSCE-203', 'description' => 'Kinematics and kinetics of particles and bodies.'],
                        ['code' => 'BSCE-213', 'title' => 'Fluid Mechanics', 'units' => 3, 'description' => 'Fluid properties, hydrostatics, and flow.'],
                        ['code' => 'BSCE-214', 'title' => 'Surveying 2', 'units' => 3, 'preReq' => 'BSCE-205', 'description' => 'Topographic and route surveying.'],
                        ['code' => 'BSCE-215', 'title' => 'Geology and Engineering', 'units' => 3, 'description' => 'Geological factors in civil engineering projects.'],
                    ],
                ],
                '3rd Year' => [
                    '1st Semester' => [
                        ['code' => 'BSCE-301', 'title' => 'Mechanics of Materials', 'units' => 3, 'preReq' => 'BSCE-212', 'description' => 'Stress, strain, and material deformation.'],
                        ['code' => 'BSCE-302', 'title' => 'Structural Theory 1', 'units' => 3, 'description' => 'Analysis of statically determinate structures.'],
                        ['code' => 'BSCE-303', 'title' => 'Highway Engineering', 'units' => 3, 'description' => 'Road design, pavements, and traffic.'],
                        ['code' => 'BSCE-304', 'title' => 'Hydraulics and Hydraulic Machinery', 'units' => 3, 'preReq' => 'BSCE-213', 'description' => 'Pipe flow, open channels, and pumps.'],
                        ['code' => 'BSCE-305', 'title' => 'Engineering Economy', 'units' => 3, 'description' => 'Economic analysis of engineering decisions.'],
                    ],
                    '2nd Semester' => [
                        ['code' => 'BSCE-311', 'title' => 'Structural Theory 2', 'units' => 3, 'preReq' => 'BSCE-302', 'description' => 'Analysis of statically indeterminate structures.'],
                        ['code' => 'BSCE-312', 'title' => 'Geotechnical Engineering 1', 'units' => 3, 'description' => 'Soil classification, compaction, and permeability.'],
                        ['code' => 'BSCE-313', 'title' => 'Water Resources Engineering', 'units' => 3, 'description' => 'Hydrology and water supply systems.'],
                        ['code' => 'BSCE-314', 'title' => 'Construction Methods and Project Management', 'units' => 3, 'description' => 'Construction planning, scheduling, and site management.'],
                        ['code' => 'BSCE-315', 'title' => 'Environmental Engineering', 'units' => 3, 'description' => 'Water treatment, waste management, and environmental impact.'],
                    ],
                ],
                '4th Year' => [
                    '1st Semester' => [
                        ['code' => 'BSCE-401', 'title' => 'Reinforced Concrete Design', 'units' => 3, 'preReq' => 'BSCE-311', 'description' => 'Design of beams, columns, and slabs.'],
                        ['code' => 'BSCE-402', 'title' => 'Geotechnical Engineering 2', 'units' => 3, 'preReq' => 'BSCE-312', 'description' => 'Foundation design and slope stability.'],
                        ['code' => 'BSCE-403', 'title' => 'Transportation Engineering', 'units' => 3, 'description' => 'Traffic engineering and transportation planning.'],
                        ['code' => 'BSCE-404', 'title' => 'Thesis / Research 1', 'units' => 3, 'description' => 'Engineering research proposal.'],
                    ],
                    '2nd Semester' => [
                        ['code' => 'BSCE-411', 'title' => 'Steel and Timber Design', 'units' => 3, 'description' => 'Structural design using steel and wood.'],
                        ['code' => 'BSCE-412', 'title' => 'Quantity Surveying and Estimating', 'units' => 3, 'description' => 'Cost estimation and project billing.'],
                        ['code' => 'BSCE-413', 'title' => 'Thesis / Research 2', 'units' => 3, 'preReq' => 'BSCE-404', 'description' => 'Research implementation and analysis.'],
                    ],
                ],
                '5th Year' => [
                    '1st Semester' => [
                        ['code' => 'BSCE-501', 'title' => 'CE Board Exam Review 1', 'units' => 3, 'description' => 'Review of mathematics and basic engineering.'],
                        ['code' => 'BSCE-502', 'title' => 'Practicum / OJT', 'units' => 6, 'description' => 'On-the-job training in an engineering firm.'],
                        ['code' => 'BSCE-503', 'title' => 'Thesis / Research 3', 'units' => 3, 'preReq' => 'BSCE-413', 'description' => 'Final defense and documentation.'],
                    ],
                    '2nd Semester' => [
                        ['code' => 'BSCE-511', 'title' => 'CE Board Exam Review 2', 'units' => 3, 'description' => 'Review of structural and geotechnical engineering.'],
                        ['code' => 'BSCE-512', 'title' => 'Engineering Ethics and Laws', 'units' => 3, 'description' => 'RA 544 and professional engineering ethics.'],
                    ],
                ],
            ],

            // ─────────────────────────────────────────────────────────────────
            // BSBA — BS in Business Administration
            // ─────────────────────────────────────────────────────────────────
            'BSBA' => [
                '1st Year' => [
                    '1st Semester' => [
                        ['code' => 'BSBA-101', 'title' => 'Introduction to Business', 'units' => 3, 'description' => 'Fundamentals of business organization and management.'],
                        ['code' => 'BSBA-102', 'title' => 'Business Mathematics', 'units' => 3, 'description' => 'Mathematical applications in business.'],
                        ['code' => 'BSBA-103', 'title' => 'Purposive Communication', 'units' => 3, 'description' => 'Business writing and communication.'],
                        ['code' => 'BSBA-104', 'title' => 'Understanding the Self', 'units' => 3, 'description' => 'Personal leadership development.'],
                        ['code' => 'BSBA-105', 'title' => 'Physical Education 1', 'units' => 2, 'description' => 'Physical wellness.'],
                        ['code' => 'BSBA-106', 'title' => 'NSTP 1', 'units' => 3, 'description' => 'National service training.'],
                    ],
                    '2nd Semester' => [
                        ['code' => 'BSBA-111', 'title' => 'Principles of Management', 'units' => 3, 'description' => 'Planning, organizing, leading, and controlling.'],
                        ['code' => 'BSBA-112', 'title' => 'Microeconomics', 'units' => 3, 'description' => 'Supply, demand, and market equilibrium.'],
                        ['code' => 'BSBA-113', 'title' => 'Accounting Fundamentals', 'units' => 3, 'description' => 'Basic accounting for non-accountants.'],
                        ['code' => 'BSBA-114', 'title' => 'Business Law', 'units' => 3, 'description' => 'Legal environment of Philippine business.'],
                        ['code' => 'BSBA-115', 'title' => 'Readings in Philippine History', 'units' => 3, 'description' => 'Philippine history.'],
                        ['code' => 'BSBA-116', 'title' => 'NSTP 2', 'units' => 3, 'description' => 'Community service.'],
                    ],
                ],
                '2nd Year' => [
                    '1st Semester' => [
                        ['code' => 'BSBA-201', 'title' => 'Marketing Management', 'units' => 3, 'description' => 'Marketing mix, consumer behavior, and market research.'],
                        ['code' => 'BSBA-202', 'title' => 'Financial Management', 'units' => 3, 'description' => 'Financial planning and investment decisions.'],
                        ['code' => 'BSBA-203', 'title' => 'Human Resource Management', 'units' => 3, 'description' => 'Recruitment, training, and compensation.'],
                        ['code' => 'BSBA-204', 'title' => 'Macroeconomics', 'units' => 3, 'description' => 'National economy, fiscal policy, and monetary policy.'],
                        ['code' => 'BSBA-205', 'title' => 'Business Statistics', 'units' => 3, 'description' => 'Statistical methods for business decision making.'],
                    ],
                    '2nd Semester' => [
                        ['code' => 'BSBA-211', 'title' => 'Operations Management', 'units' => 3, 'description' => 'Production planning and quality management.'],
                        ['code' => 'BSBA-212', 'title' => 'Strategic Management', 'units' => 3, 'description' => 'Competitive strategy and organizational planning.'],
                        ['code' => 'BSBA-213', 'title' => 'Business Research Methods', 'units' => 3, 'description' => 'Applied research for business problems.'],
                        ['code' => 'BSBA-214', 'title' => 'E-Commerce', 'units' => 3, 'description' => 'Online business models and digital marketing.'],
                    ],
                ],
                '3rd Year' => [
                    '1st Semester' => [
                        ['code' => 'BSBA-301', 'title' => 'International Business', 'units' => 3, 'description' => 'Global trade, foreign investment, and multinational firms.'],
                        ['code' => 'BSBA-302', 'title' => 'Supply Chain Management', 'units' => 3, 'description' => 'Logistics, procurement, and supply chain optimization.'],
                        ['code' => 'BSBA-303', 'title' => 'Business Ethics and CSR', 'units' => 3, 'description' => 'Corporate social responsibility and ethical leadership.'],
                        ['code' => 'BSBA-304', 'title' => 'Business Analytics', 'units' => 3, 'description' => 'Data-driven decision making in business.'],
                        ['code' => 'BSBA-305', 'title' => 'Capstone Business Plan 1', 'units' => 3, 'description' => 'Business plan development and feasibility study.'],
                    ],
                    '2nd Semester' => [
                        ['code' => 'BSBA-311', 'title' => 'Practicum / OJT', 'units' => 6, 'description' => 'Industry exposure in a business organization.'],
                        ['code' => 'BSBA-312', 'title' => 'Capstone Business Plan 2', 'units' => 3, 'preReq' => 'BSBA-305', 'description' => 'Business plan refinement and presentation.'],
                        ['code' => 'BSBA-313', 'title' => 'Technopreneurship', 'units' => 3, 'description' => 'Tech-enabled business ventures.'],
                    ],
                ],
                '4th Year' => [
                    '1st Semester' => [
                        ['code' => 'BSBA-401', 'title' => 'Management Information Systems', 'units' => 3, 'description' => 'Information systems for managerial decisions.'],
                        ['code' => 'BSBA-402', 'title' => 'Total Quality Management', 'units' => 3, 'description' => 'Quality standards, ISO, and continuous improvement.'],
                        ['code' => 'BSBA-403', 'title' => 'Seminar in Contemporary Business', 'units' => 3, 'description' => 'Current issues in Philippine and global business.'],
                    ],
                    '2nd Semester' => [
                        ['code' => 'BSBA-411', 'title' => 'Business Policy and Strategy', 'units' => 3, 'description' => 'Integrative capstone applying all business disciplines.'],
                        ['code' => 'BSBA-412', 'title' => 'Professional Ethics', 'units' => 3, 'description' => 'Professional conduct in the business world.'],
                    ],
                ],
            ],

            // ─────────────────────────────────────────────────────────────────
            // BSN — BS in Nursing
            // ─────────────────────────────────────────────────────────────────
            'BSN' => [
                '1st Year' => [
                    '1st Semester' => [
                        ['code' => 'BSN-101', 'title' => 'Anatomy and Physiology 1', 'units' => 3, 'description' => 'Structure and function of the human body systems.'],
                        ['code' => 'BSN-102', 'title' => 'Biochemistry', 'units' => 3, 'description' => 'Chemical processes in living organisms.'],
                        ['code' => 'BSN-103', 'title' => 'Purposive Communication', 'units' => 3, 'description' => 'Communication in healthcare settings.'],
                        ['code' => 'BSN-104', 'title' => 'Understanding the Self', 'units' => 3, 'description' => 'Self-care and resilience for nursing students.'],
                        ['code' => 'BSN-105', 'title' => 'Physical Education 1', 'units' => 2, 'description' => 'Physical fitness.'],
                        ['code' => 'BSN-106', 'title' => 'NSTP 1', 'units' => 3, 'description' => 'National service training.'],
                    ],
                    '2nd Semester' => [
                        ['code' => 'BSN-111', 'title' => 'Anatomy and Physiology 2', 'units' => 3, 'preReq' => 'BSN-101', 'description' => 'Advanced body systems: reproductive, endocrine, and neurological.'],
                        ['code' => 'BSN-112', 'title' => 'Microbiology and Parasitology', 'units' => 3, 'description' => 'Pathogens, infections, and host-parasite relationships.'],
                        ['code' => 'BSN-113', 'title' => 'Nutrition and Dietetics', 'units' => 3, 'description' => 'Nutritional requirements and therapeutic diets.'],
                        ['code' => 'BSN-114', 'title' => 'Fundamentals of Nursing', 'units' => 5, 'description' => 'Basic nursing concepts, the nursing process, and skills.'],
                        ['code' => 'BSN-115', 'title' => 'Readings in Philippine History', 'units' => 3, 'description' => 'Philippine history.'],
                        ['code' => 'BSN-116', 'title' => 'NSTP 2', 'units' => 3, 'description' => 'Community service.'],
                    ],
                ],
                '2nd Year' => [
                    '1st Semester' => [
                        ['code' => 'BSN-201', 'title' => 'Medical-Surgical Nursing 1', 'units' => 5, 'preReq' => 'BSN-114', 'description' => 'Care of adult patients with medical-surgical conditions.'],
                        ['code' => 'BSN-202', 'title' => 'Pharmacology', 'units' => 3, 'description' => 'Drug classifications, actions, and nursing implications.'],
                        ['code' => 'BSN-203', 'title' => 'Pathophysiology', 'units' => 3, 'description' => 'Disease mechanisms and clinical manifestations.'],
                        ['code' => 'BSN-204', 'title' => 'Health Assessment', 'units' => 3, 'description' => 'Physical examination and health history taking.'],
                    ],
                    '2nd Semester' => [
                        ['code' => 'BSN-211', 'title' => 'Medical-Surgical Nursing 2', 'units' => 5, 'preReq' => 'BSN-201', 'description' => 'Complex surgical nursing care.'],
                        ['code' => 'BSN-212', 'title' => 'Maternal and Child Nursing', 'units' => 5, 'description' => 'Nursing care for mothers, newborns, and children.'],
                        ['code' => 'BSN-213', 'title' => 'Psychiatric Nursing', 'units' => 4, 'description' => 'Mental health nursing and therapeutic communication.'],
                        ['code' => 'BSN-214', 'title' => 'Research in Nursing', 'units' => 3, 'description' => 'Evidence-based practice and nursing research.'],
                    ],
                ],
                '3rd Year' => [
                    '1st Semester' => [
                        ['code' => 'BSN-301', 'title' => 'Community Health Nursing', 'units' => 5, 'description' => 'Public health nursing and community-based care.'],
                        ['code' => 'BSN-302', 'title' => 'Pediatric Nursing', 'units' => 4, 'description' => 'Nursing care of infants, children, and adolescents.'],
                        ['code' => 'BSN-303', 'title' => 'Critical Care Nursing', 'units' => 3, 'description' => 'ICU and emergency nursing management.'],
                        ['code' => 'BSN-304', 'title' => 'Nursing Leadership and Management', 'units' => 3, 'description' => 'Healthcare management and nursing administration.'],
                    ],
                    '2nd Semester' => [
                        ['code' => 'BSN-311', 'title' => 'Gerontological Nursing', 'units' => 3, 'description' => 'Nursing care for elderly patients.'],
                        ['code' => 'BSN-312', 'title' => 'Operating Room Nursing', 'units' => 3, 'description' => 'Perioperative nursing practice.'],
                        ['code' => 'BSN-313', 'title' => 'Disaster Nursing', 'units' => 3, 'description' => 'Nursing response to mass casualty events.'],
                        ['code' => 'BSN-314', 'title' => 'Legal Aspects of Nursing', 'units' => 2, 'description' => 'Nursing law, RA 9173, and professional accountability.'],
                    ],
                ],
                '4th Year' => [
                    '1st Semester' => [
                        ['code' => 'BSN-401', 'title' => 'Clinical Practicum 1', 'units' => 9, 'description' => 'Hospital-based clinical immersion.'],
                        ['code' => 'BSN-402', 'title' => 'NLE Review 1', 'units' => 3, 'description' => 'Comprehensive nursing licensure review — fundamentals and med-surg.'],
                    ],
                    '2nd Semester' => [
                        ['code' => 'BSN-411', 'title' => 'Clinical Practicum 2', 'units' => 9, 'description' => 'Specialized clinical exposure: OR, ICU, community.'],
                        ['code' => 'BSN-412', 'title' => 'NLE Review 2', 'units' => 3, 'description' => 'Comprehensive nursing licensure review — maternal, peds, psych.'],
                    ],
                ],
            ],

            // ─────────────────────────────────────────────────────────────────
            // BEEd — Bachelor of Elementary Education
            // ─────────────────────────────────────────────────────────────────
            'BEEd' => [
                '1st Year' => [
                    '1st Semester' => [
                        ['code' => 'BEEd-101', 'title' => 'The Child and Adolescent Learner', 'units' => 3, 'description' => 'Development and learning characteristics of children.'],
                        ['code' => 'BEEd-102', 'title' => 'The Teaching Profession', 'units' => 3, 'description' => 'Teaching as a vocation, ethics, and professionalism.'],
                        ['code' => 'BEEd-103', 'title' => 'Purposive Communication', 'units' => 3, 'description' => 'Oral and written communication for educators.'],
                        ['code' => 'BEEd-104', 'title' => 'Understanding the Self', 'units' => 3, 'description' => 'Teacher identity and wellness.'],
                        ['code' => 'BEEd-105', 'title' => 'Physical Education 1', 'units' => 2, 'description' => 'Physical education for future PE teachers.'],
                        ['code' => 'BEEd-106', 'title' => 'NSTP 1', 'units' => 3, 'description' => 'National service training.'],
                    ],
                    '2nd Semester' => [
                        ['code' => 'BEEd-111', 'title' => 'Facilitating Learner-Centered Teaching', 'units' => 3, 'description' => 'Constructivist and active learning strategies.'],
                        ['code' => 'BEEd-112', 'title' => 'The Teacher and the School Curriculum', 'units' => 3, 'description' => 'Curriculum design and the K-12 framework.'],
                        ['code' => 'BEEd-113', 'title' => 'Content and Pedagogy — Language Arts', 'units' => 3, 'description' => 'Teaching reading, writing, and language in the elementary grades.'],
                        ['code' => 'BEEd-114', 'title' => 'Readings in Philippine History', 'units' => 3, 'description' => 'Philippine history.'],
                        ['code' => 'BEEd-115', 'title' => 'NSTP 2', 'units' => 3, 'description' => 'Community service.'],
                    ],
                ],
                '2nd Year' => [
                    '1st Semester' => [
                        ['code' => 'BEEd-201', 'title' => 'Content and Pedagogy — Mathematics', 'units' => 3, 'description' => 'Teaching elementary mathematics.'],
                        ['code' => 'BEEd-202', 'title' => 'Content and Pedagogy — Science', 'units' => 3, 'description' => 'Teaching elementary science.'],
                        ['code' => 'BEEd-203', 'title' => 'Assessment in Learning 1', 'units' => 3, 'description' => 'Traditional and authentic assessment tools.'],
                        ['code' => 'BEEd-204', 'title' => 'Technology for Teaching and Learning', 'units' => 3, 'description' => 'ICT integration in the classroom.'],
                        ['code' => 'BEEd-205', 'title' => 'Special and Inclusive Education', 'units' => 3, 'description' => 'Teaching learners with special needs.'],
                    ],
                    '2nd Semester' => [
                        ['code' => 'BEEd-211', 'title' => 'Content and Pedagogy — Araling Panlipunan', 'units' => 3, 'description' => 'Teaching social studies in the elementary grades.'],
                        ['code' => 'BEEd-212', 'title' => 'Content and Pedagogy — MAPEH', 'units' => 3, 'description' => 'Music, arts, physical education, and health for elementary.'],
                        ['code' => 'BEEd-213', 'title' => 'Assessment in Learning 2', 'units' => 3, 'description' => 'Portfolio assessment and grading systems.'],
                        ['code' => 'BEEd-214', 'title' => 'Research in Education 1', 'units' => 3, 'description' => 'Introduction to educational research.'],
                    ],
                ],
                '3rd Year' => [
                    '1st Semester' => [
                        ['code' => 'BEEd-301', 'title' => 'Field Study 1', 'units' => 3, 'description' => 'Classroom observation and teaching environment analysis.'],
                        ['code' => 'BEEd-302', 'title' => 'Mother Tongue-Based Multilingual Education', 'units' => 3, 'description' => 'Teaching in the learners native language.'],
                        ['code' => 'BEEd-303', 'title' => 'Research in Education 2', 'units' => 3, 'preReq' => 'BEEd-214', 'description' => 'Action research and classroom-based inquiry.'],
                        ['code' => 'BEEd-304', 'title' => 'Classroom Management and Discipline', 'units' => 3, 'description' => 'Positive behavior management strategies.'],
                    ],
                    '2nd Semester' => [
                        ['code' => 'BEEd-311', 'title' => 'Field Study 2', 'units' => 3, 'preReq' => 'BEEd-301', 'description' => 'Assisted teaching and co-instruction.'],
                        ['code' => 'BEEd-312', 'title' => 'School Community Partnership', 'units' => 3, 'description' => 'Parent and community engagement in schools.'],
                        ['code' => 'BEEd-313', 'title' => 'Differentiated Instruction', 'units' => 3, 'description' => 'Meeting diverse learner needs through varied instruction.'],
                    ],
                ],
                '4th Year' => [
                    '1st Semester' => [
                        ['code' => 'BEEd-401', 'title' => 'Practice Teaching 1', 'units' => 6, 'description' => 'Supervised student teaching in a cooperating elementary school.'],
                        ['code' => 'BEEd-402', 'title' => 'LET Review — General Education', 'units' => 3, 'description' => 'Comprehensive review for the Licensure Exam for Teachers.'],
                    ],
                    '2nd Semester' => [
                        ['code' => 'BEEd-411', 'title' => 'Practice Teaching 2', 'units' => 6, 'preReq' => 'BEEd-401', 'description' => 'Full responsibility teaching in cooperating school.'],
                        ['code' => 'BEEd-412', 'title' => 'LET Review — Professional Education', 'units' => 3, 'description' => 'Review of professional education components for LET.'],
                    ],
                ],
            ],

            // ─────────────────────────────────────────────────────────────────
            // BSEd — Bachelor of Secondary Education
            // ─────────────────────────────────────────────────────────────────
            'BSEd' => [
                '1st Year' => [
                    '1st Semester' => [
                        ['code' => 'BSEd-101', 'title' => 'The Adolescent Learner', 'units' => 3, 'description' => 'Development and psychology of secondary school learners.'],
                        ['code' => 'BSEd-102', 'title' => 'The Teaching Profession', 'units' => 3, 'description' => 'Professional identity and ethics in secondary education.'],
                        ['code' => 'BSEd-103', 'title' => 'Purposive Communication', 'units' => 3, 'description' => 'Communication skills for secondary teachers.'],
                        ['code' => 'BSEd-104', 'title' => 'Understanding the Self', 'units' => 3, 'description' => 'Teacher self-awareness and resilience.'],
                        ['code' => 'BSEd-105', 'title' => 'Physical Education 1', 'units' => 2, 'description' => 'Physical wellness.'],
                        ['code' => 'BSEd-106', 'title' => 'NSTP 1', 'units' => 3, 'description' => 'National service training.'],
                    ],
                    '2nd Semester' => [
                        ['code' => 'BSEd-111', 'title' => 'Facilitating Learner-Centered Teaching', 'units' => 3, 'description' => 'Active learning and constructivism in secondary classrooms.'],
                        ['code' => 'BSEd-112', 'title' => 'Secondary Curriculum Design', 'units' => 3, 'description' => 'Curriculum frameworks and the K-12 senior high school curriculum.'],
                        ['code' => 'BSEd-113', 'title' => 'Major Subject 1', 'units' => 3, 'description' => 'Specialization content knowledge — first course.'],
                        ['code' => 'BSEd-114', 'title' => 'Readings in Philippine History', 'units' => 3, 'description' => 'Philippine history.'],
                        ['code' => 'BSEd-115', 'title' => 'NSTP 2', 'units' => 3, 'description' => 'Community service.'],
                    ],
                ],
                '2nd Year' => [
                    '1st Semester' => [
                        ['code' => 'BSEd-201', 'title' => 'Major Subject 2', 'units' => 3, 'description' => 'Specialization content — second course.'],
                        ['code' => 'BSEd-202', 'title' => 'Pedagogy in the Secondary School', 'units' => 3, 'description' => 'Teaching strategies for secondary learners.'],
                        ['code' => 'BSEd-203', 'title' => 'Assessment in Learning 1', 'units' => 3, 'description' => 'Traditional and performance-based assessment.'],
                        ['code' => 'BSEd-204', 'title' => 'Technology for Teaching', 'units' => 3, 'description' => 'EdTech tools and digital learning integration.'],
                        ['code' => 'BSEd-205', 'title' => 'Special and Inclusive Education', 'units' => 3, 'description' => 'Differentiated strategies for diverse secondary learners.'],
                    ],
                    '2nd Semester' => [
                        ['code' => 'BSEd-211', 'title' => 'Major Subject 3', 'units' => 3, 'description' => 'Specialization content — third course.'],
                        ['code' => 'BSEd-212', 'title' => 'Assessment in Learning 2', 'units' => 3, 'description' => 'Portfolio, rubrics, and standards-based grading.'],
                        ['code' => 'BSEd-213', 'title' => 'Research in Secondary Education', 'units' => 3, 'description' => 'Action research design and implementation.'],
                        ['code' => 'BSEd-214', 'title' => 'Classroom Management', 'units' => 3, 'description' => 'Managing the secondary classroom effectively.'],
                    ],
                ],
                '3rd Year' => [
                    '1st Semester' => [
                        ['code' => 'BSEd-301', 'title' => 'Major Subject 4', 'units' => 3, 'description' => 'Specialization content — fourth course.'],
                        ['code' => 'BSEd-302', 'title' => 'Field Study 1', 'units' => 3, 'description' => 'Observation and participation in a secondary school.'],
                        ['code' => 'BSEd-303', 'title' => 'Teaching Major Subject Methods 1', 'units' => 3, 'description' => 'Pedagogical approaches specific to the specialization.'],
                        ['code' => 'BSEd-304', 'title' => 'Guidance and Counseling', 'units' => 3, 'description' => 'Guidance services and school counseling basics.'],
                    ],
                    '2nd Semester' => [
                        ['code' => 'BSEd-311', 'title' => 'Major Subject 5', 'units' => 3, 'description' => 'Specialization content — fifth course.'],
                        ['code' => 'BSEd-312', 'title' => 'Field Study 2', 'units' => 3, 'preReq' => 'BSEd-302', 'description' => 'Assisted instruction in a cooperating secondary school.'],
                        ['code' => 'BSEd-313', 'title' => 'Teaching Major Subject Methods 2', 'units' => 3, 'description' => 'Advanced pedagogical methods in the specialization.'],
                    ],
                ],
                '4th Year' => [
                    '1st Semester' => [
                        ['code' => 'BSEd-401', 'title' => 'Practice Teaching 1', 'units' => 6, 'description' => 'Supervised teaching in a cooperating secondary school.'],
                        ['code' => 'BSEd-402', 'title' => 'LET Review — General Education', 'units' => 3, 'description' => 'Comprehensive review for the Licensure Exam for Teachers.'],
                    ],
                    '2nd Semester' => [
                        ['code' => 'BSEd-411', 'title' => 'Practice Teaching 2', 'units' => 6, 'preReq' => 'BSEd-401', 'description' => 'Full responsibility teaching in cooperating school.'],
                        ['code' => 'BSEd-412', 'title' => 'LET Review — Professional Education', 'units' => 3, 'description' => 'Professional education review for LET.'],
                    ],
                ],
            ],

            // ─────────────────────────────────────────────────────────────────
            // Remaining programs: representative curriculum sets
            // (BSME, BSEE, BSArch, BSEntrep, BSTM, BSHM, BAComm, BSPharm, BSMT)
            // These follow the same pattern. Abbreviated for key year/term combos.
            // ─────────────────────────────────────────────────────────────────

            'BSME' => $this->engineeringTemplate('BSME', 'BS Mechanical Engineering', [
                '1st Semester' => ['Calculus 1', 'Engineering Drawing', 'Chemistry for Engineers', 'Purposive Communication', 'Understanding the Self', 'Physical Education 1', 'NSTP 1'],
                '2nd Semester' => ['Calculus 2', 'Physics for Engineers 1', 'Statics of Rigid Bodies', 'Readings in Philippine History', 'Physical Education 2', 'NSTP 2'],
            ]),

            'BSEE' => $this->engineeringTemplate('BSEE', 'BS Electrical Engineering', [
                '1st Semester' => ['Calculus 1', 'Engineering Drawing', 'Chemistry for Engineers', 'Purposive Communication', 'Understanding the Self', 'Physical Education 1', 'NSTP 1'],
                '2nd Semester' => ['Calculus 2', 'Physics for Engineers 1', 'Circuit Theory 1', 'Readings in Philippine History', 'Physical Education 2', 'NSTP 2'],
            ]),

            'BSArch' => $this->engineeringTemplate('BSArch', 'BS Architecture', [
                '1st Semester' => ['Architectural Design 1', 'History of Architecture 1', 'Visual Communication 1', 'Purposive Communication', 'Understanding the Self', 'Physical Education 1', 'NSTP 1'],
                '2nd Semester' => ['Architectural Design 2', 'History of Architecture 2', 'Building Technology 1', 'Readings in Philippine History', 'Physical Education 2', 'NSTP 2'],
            ]),

            'BSEntrep' => $this->businessTemplate('BSEntrep', 'BS Entrepreneurship', [
                '1st Semester' => ['Introduction to Entrepreneurship', 'Business Mathematics', 'Purposive Communication', 'Understanding the Self', 'Physical Education 1', 'NSTP 1'],
                '2nd Semester' => ['Principles of Management', 'Microeconomics', 'Accounting Fundamentals', 'Readings in Philippine History', 'Physical Education 2', 'NSTP 2'],
            ]),

            'BSTM' => $this->hospitalityTemplate('BSTM', 'BS Tourism Management', [
                '1st Semester' => ['Introduction to Tourism', 'Travel Agency Operations', 'Purposive Communication', 'Understanding the Self', 'Physical Education 1', 'NSTP 1'],
                '2nd Semester' => ['Tour Guiding', 'Tourism Geography', 'Hospitality Marketing', 'Readings in Philippine History', 'Physical Education 2', 'NSTP 2'],
            ]),

            'BSHM' => $this->hospitalityTemplate('BSHM', 'BS Hospitality Management', [
                '1st Semester' => ['Introduction to Hospitality Industry', 'Food and Beverage Service', 'Purposive Communication', 'Understanding the Self', 'Physical Education 1', 'NSTP 1'],
                '2nd Semester' => ['Front Office Operations', 'Housekeeping Management', 'Culinary Arts 1', 'Readings in Philippine History', 'Physical Education 2', 'NSTP 2'],
            ]),

            'BAComm' => $this->artsTemplate('BAComm', 'BA Communication', [
                '1st Semester' => ['Introduction to Communication', 'Writing for Mass Media', 'Purposive Communication', 'Understanding the Self', 'Physical Education 1', 'NSTP 1'],
                '2nd Semester' => ['Media and Society', 'Broadcast Communication', 'Photography', 'Readings in Philippine History', 'Physical Education 2', 'NSTP 2'],
            ]),

            'BSPharm' => $this->healthTemplate('BSPharm', 'BS Pharmacy', [
                '1st Semester' => ['General Chemistry 1', 'Biology 1', 'Purposive Communication', 'Understanding the Self', 'Physical Education 1', 'NSTP 1'],
                '2nd Semester' => ['General Chemistry 2', 'Microbiology', 'Anatomy and Physiology', 'Readings in Philippine History', 'Physical Education 2', 'NSTP 2'],
            ]),

            'BSMT' => $this->healthTemplate('BSMT', 'BS Medical Technology', [
                '1st Semester' => ['General Chemistry 1', 'Biology 1', 'Purposive Communication', 'Understanding the Self', 'Physical Education 1', 'NSTP 1'],
                '2nd Semester' => ['Hematology 1', 'Microbiology', 'Anatomy and Physiology', 'Readings in Philippine History', 'Physical Education 2', 'NSTP 2'],
            ]),
        ];
    }

    /**
     * Generic template generators for programs with similar structures.
     * These build a full 4-year (8-semester) curriculum automatically
     * from a given 1st Year subject list, generating realistic year 2-4 subjects.
     */
    private function engineeringTemplate(string $code, string $name, array $firstYearSubjects): array
    {
        return $this->buildGenericCurriculum($code, $name, $firstYearSubjects, 5, [
            'Mathematics', 'Thermodynamics', 'Fluid Mechanics', 'Materials Science',
            'Machine Design', 'Engineering Economy', 'Control Systems', 'Capstone Project',
            'Practicum', 'Board Exam Review', 'Engineering Ethics', 'Technical Elective',
        ]);
    }

    private function businessTemplate(string $code, string $name, array $firstYearSubjects): array
    {
        return $this->buildGenericCurriculum($code, $name, $firstYearSubjects, 4, [
            'Marketing Management', 'Financial Management', 'Operations Management',
            'Human Resource Management', 'Strategic Management', 'Business Research',
            'Technopreneurship', 'Capstone Business Plan', 'Practicum', 'Business Ethics',
        ]);
    }

    private function hospitalityTemplate(string $code, string $name, array $firstYearSubjects): array
    {
        return $this->buildGenericCurriculum($code, $name, $firstYearSubjects, 4, [
            'Revenue Management', 'Event Management', 'Food Safety and Sanitation',
            'Hospitality Law', 'Sustainable Tourism', 'Customer Service Management',
            'Practicum', 'Capstone Project', 'Professional Ethics', 'Research Methods',
        ]);
    }

    private function artsTemplate(string $code, string $name, array $firstYearSubjects): array
    {
        return $this->buildGenericCurriculum($code, $name, $firstYearSubjects, 4, [
            'Media Production', 'Journalism', 'Public Relations', 'Advertising',
            'Digital Media', 'Media Ethics', 'Film Studies', 'Research Methods',
            'Practicum', 'Capstone Project',
        ]);
    }

    private function healthTemplate(string $code, string $name, array $firstYearSubjects): array
    {
        return $this->buildGenericCurriculum($code, $name, $firstYearSubjects, 4, [
            'Clinical Chemistry', 'Pharmacology', 'Immunology', 'Parasitology',
            'Histopathology', 'Clinical Practicum', 'Board Exam Review',
            'Research Methods', 'Professional Ethics', 'Hospital Practicum',
        ]);
    }

    private function buildGenericCurriculum(
        string $code,
        string $name,
        array $firstYearSubjects,
        int $years,
        array $upperSubjects
    ): array {
        $yearLabels = ['1st Year', '2nd Year', '3rd Year', '4th Year', '5th Year'];
        $curriculum = [];

        foreach (range(0, $years - 1) as $y) {
            $yearLabel = $yearLabels[$y];
            $curriculum[$yearLabel] = [];

            foreach (['1st Semester', '2nd Semester'] as $termIdx => $term) {
                $subjects = [];

                if ($y === 0) {
                    // Use the real first-year subject names provided
                    $termSubjects = array_values($firstYearSubjects)[$termIdx] ?? [];
                    foreach ($termSubjects as $idx => $subjectTitle) {
                        $subCode = $code . '-' . str_pad(($y * 20) + ($termIdx * 10) + $idx + 1, 3, '0', STR_PAD_LEFT);
                        $subjects[] = [
                            'code'        => $subCode,
                            'title'       => $subjectTitle,
                            'units'       => in_array($subjectTitle, ['Physical Education 1', 'Physical Education 2']) ? 2 : 3,
                            'description' => "Core subject for {$name} students.",
                        ];
                    }
                } else {
                    // Generate upper-year subjects from the provided subject pool
                    $poolSize = count($upperSubjects);
                    $startIdx = (($y - 1) * 2 + $termIdx) % $poolSize;
                    $count = min(5, $poolSize);

                    for ($i = 0; $i < $count; $i++) {
                        $subjectTitle = $upperSubjects[($startIdx + $i) % $poolSize];
                        $subCode = $code . '-' . str_pad(($y * 20) + ($termIdx * 10) + $i + 1, 3, '0', STR_PAD_LEFT);
                        $subjects[] = [
                            'code'        => $subCode,
                            'title'       => $subjectTitle . ' ' . ($y) . ($termIdx === 0 ? 'A' : 'B'),
                            'units'       => 3,
                            'description' => "Year {$yearLabel} {$term} subject for {$name}.",
                        ];
                    }
                }

                $curriculum[$yearLabel][$term] = $subjects;
            }
        }

        return $curriculum;
    }
}